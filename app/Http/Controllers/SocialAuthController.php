<?php
namespace App\Http\Controllers;

use App\Core\Interface\Controllers\BaseController;
use App\Modules\Authentication\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Authentication\Infrastructure\Persistence\Eloquent\Models\User;
use App\Modules\Authentication\Domain\ValueObjects\Email;
use App\Modules\Authentication\Infrastructure\Persistence\Eloquent\Models\User as ModelsUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends BaseController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }
    /**
     * Rediriger vers le provider OAuth
     */
    public function redirect($provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    /**
     * Gérer le callback du provider OAuth
     */
    public function callback($provider)
    {
        $this->validateProvider($provider);

        try {
            // Récupérer les informations de l'utilisateur depuis le provider
            $socialUser = Socialite::driver($provider)->stateless()->user();
            // Trouver ou créer l'utilisateur
            $user = $this->findOrCreateUser($socialUser, $provider);

            // Créer un token Passport
            $token = $this->userRepository->generatPassportToken($user->id);
            // Log::info("Generated token for user ID {$user->id} : {$token}");

            // Rediriger vers le frontend avec le token
            $redirectUrl = sprintf(
                '%s/auth/callback?token=%s&user=%s',
                env('FRONTEND_URL'),
                $token,
                urlencode(json_encode([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // 'avatar' => $user->avatar,
                ]))
            );

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            Log::error('Social auth callback error: '.$e->getMessage());
            // En cas d'erreur, rediriger vers le frontend avec l'erreur
            $errorUrl = sprintf(
                '%s/auth/callback?error=%s',
                env('FRONTEND_URL'),
                urlencode('Échec de l\'authentification')
            );

            return redirect($errorUrl);
        }
    }

    /**
     * Trouver ou créer un utilisateur basé sur les infos du provider
     */
    protected function findOrCreateUser($socialUser, $provider)
    {
        // Chercher un utilisateur avec ce provider et provider_id
        $user = ModelsUser::where('auth_provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($user) {
            return $user;
        }
        $email = new Email($socialUser->getEmail());
        // Vérifier si un utilisateur existe avec cet email
        $existingUser = User::where('email', $email->value())->first();

        if ($existingUser) {
            // Lier le compte existant au provider social
            $existingUser->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                // 'avatar' => $socialUser->getAvatar(),
            ]);

            return $existingUser;
        }

        // Créer un nouvel utilisateur
        return User::create([
            'fullname' => $socialUser->getName(),
            'email' => $email->value(),
            'auth_provider' => $provider,
            'provider_id' => $socialUser->getId(),
            // 'avatar' => $socialUser->getAvatar(),
            'password' => Hash::make(Str::random(24)),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Valider le provider
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }
    }
}