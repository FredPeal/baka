<?php

namespace Baka\Test\Integration\Auth;

use Baka\Auth\Auth;
use Baka\Auth\Models\Users;
use Baka\Auth\UserProvider;
use Baka\Contracts\Auth\UserInterface;
use Baka\Hashing\Keys;
use PhalconUnitTestCase;

class AuthTest extends PhalconUnitTestCase
{
    /**
     * Test user signup.
     *
     * @return boolean
     */
    public function testSignUp()
    {
        UserProvider::set(new Users());

        $userData = [
            'email' => $this->faker->email,
            'password' => 'nonenone',
            'name' => $this->faker->name,
            'defaultCompanyName' => $this->faker->name,
            'displayname' => $this->faker->firstname,
        ];

        $user = Auth::signUp($userData);

        $this->assertTrue($user instanceof UserInterface);
    }

    /**
     * Test userlogin.
     *
     * @return boolean
     */
    public function testSessionGenerate()
    {
        $session = new \Baka\Auth\Models\Sessions();

        $userData = $session->start(
            UserProvider::get()::findFirst(),
            $this->faker->uuid,
            $this->faker->sha256,
            $this->faker->ipv4,
            1
        );

        $this->assertTrue($userData instanceof Users);
    }

    /**
     * Test user logi.
     *
     * @return boolean
     */
    public function testLogin()
    {
        $user = UserProvider::get()::findFirst();

        $email = $user->email;
        $password = 'nonenone';
        $remember = 1;
        $admin = 0;
        $userIp = $this->faker->ipv4;

        $userData = Auth::login($email, $password, $remember, $admin, $userIp);

        $this->assertTrue($userData instanceof Users);
    }

    /**
     * Logout.
     *
     * @return boolean
     */
    public function testLogout()
    {
        $user = UserProvider::get()::findFirst();

        $email = $user->email;
        $password = 'nonenone';
        $remember = 1;
        $admin = 0;
        $userIp = $this->faker->ipv4;

        $userData = Auth::login($email, $password, $remember, $admin, $userIp);
        $this->assertTrue($userData->logout());
    }

    /**
     * Test user forgot password.
     *
     * @return boolean
     */
    public function testForgotPassword()
    {
        $user = UserProvider::get()::findFirst();

        $email = $user->email;
        /**
         * check if the user email exist
         * if it does create the user activation key to send
         * send the user email.
         *
         * if it doesn't exist then send the error msg
         */
        if ($recoverUser = UserProvider::get()::getByEmail($email)) {
            $recoverUser->user_activation_forgot = Keys::make();
            $recoverUser->update();

            return $this->assertTrue(strlen($recoverUser->user_activation_forgot) > 0);
        }

        return $this->assertTrue($recoverUser instanceof UserInterface);
    }
}
