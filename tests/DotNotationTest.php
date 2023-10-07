<?php
use PHPUnit\Framework\TestCase;
use Sajadsdi\ArrayDotNotation\DotNotation;

class DotNotationTest extends TestCase
{
    public function testGetSingleValue()
    {
        $data = [
            'user' => [
                'profile' => [
                    'id' => 625,
                    'pic' => '625.png',
                ],
            ],
        ];

        $dotNotation = new DotNotation($data);

        $userId = $dotNotation->get('user.profile.id');
        $this->assertEquals(625, $userId);
    }

    public function testGetMultipleValues()
    {
        $settings = [
            'app' => [
                'name' => 'My App',
                'version' => '1.0',
            ],
            'user' => [
                'theme' => 'light',
            ],
        ];

        $dotNotation = new DotNotation($settings);

        $result = $dotNotation->get('app.name', 'app.version', 'user');
        $expected = [
            'name' => 'My App',
            'version' => '1.0',
            'user' => [
                'theme' => 'light',
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testCheckKeyExistence()
    {
        $data = [
            'user' => [
                'profile' => [
                    'id' => 625,
                    'pic' => '625.png',
                ],
            ],
        ];

        $dotNotation = new DotNotation($data);

        $keyExists = $dotNotation->has('user.profile.id');
        $this->assertTrue($keyExists);

        $keyNotExists = $dotNotation->has('user.profile.name');
        $this->assertFalse($keyNotExists);
    }

    public function testSetSingleValue()
    {
        $data = [
            'user' => [
                'profile' => [
                    'id' => 625,
                    'pic' => '625.png',
                ],
            ],
        ];

        $dotNotation = new DotNotation($data);

        $dotNotation->set(['user.profile.id' => 12345]);
        $newUserId = $dotNotation->get('user.profile.id');
        $this->assertEquals(12345, $newUserId);
    }

    public function testMapKeysForOutputDataInGet()
    {
        $data = [
            'user' => [
                'profile' => [
                    'id' => 625,
                    'pic' => '625.png',
                ],
            ],
        ];

        $dotNotation = new DotNotation($data);

        $keys = ['profile_id' => 'user.profile.id', 'profile_photo' => 'user.profile.pic'];

        $result = $dotNotation->get($keys);
        $expected = ['profile_id' => 625, 'profile_photo' => '625.png'];

        $this->assertEquals($expected, $result);
    }

    public function testAutomaticKeyHandlingForDuplicateKeys()
    {
        $array = [
            'users' => [
                ['id' => 1, 'name' => 'John'],
                ['id' => 2, 'name' => 'Alice'],
                ['id' => 3, 'name' => 'Emma'],
                ['id' => 4, 'name' => 'Emily'],
                ['id' => 5, 'name' => 'Sofia'],
            ],
        ];

        $dotNotation = new DotNotation($array);

        $keys = ['users.2.name', 'users.3.name', 'users.4.name'];

        $result = $dotNotation->get($keys);
        $expected = ['name' => 'Emma', 'name_1' => 'Emily', 'name_2' => 'Sofia'];

        $this->assertEquals($expected, $result);
    }

    public function testAutomaticKeyHandlingForNumericKeys()
    {
        $array = [
            'users' => [
                ['id' => 1, 'name' => 'John'],
                ['id' => 2, 'name' => 'Alice'],
                ['id' => 3, 'name' => 'Emma'],
                ['id' => 4, 'name' => 'Emily'],
                ['id' => 5, 'name' => 'Sofia'],
            ],
        ];

        $dotNotation = new DotNotation($array);

        $keys = ['users.2', 'users.3', 'users.4'];

        $result = $dotNotation->get($keys);
        $expected = [['id' => 3, 'name' => 'Emma'], ['id' => 4, 'name' => 'Emily'], ['id' => 5, 'name' => 'Sofia']];

        $this->assertEquals($expected, $result);
    }
}