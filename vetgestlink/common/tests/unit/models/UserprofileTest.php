<?php

namespace common\tests\unit\models;

use common\models\Userprofile;
use common\models\User;

class UserprofileTest extends \Codeception\Test\Unit
{
    public function testCreateProfile()
    {
        $profile = new Userprofile();
        $profile->user_id = 1;
        $profile->nif = '123456789';
        $profile->telemovel = '912345678';

        $this->assertTrue($profile->save());
    }

    public function testRelationship()
    {
        $user = User::findOne(1);
        $this->assertNotNull($user->profile);
    }
}
