<?php
/**
 * Created by PhpStorm.
 * User: Arun
 * Date: 14.08.19
 * Time: 16:32
 */

namespace Tests\Unit;


use App\Http\Requests\LikeAPIRequest;
use Tests\TestCase;


class ValidationTest extends TestCase
{
    protected $validator;
    protected $rules;

    public function setUp(): void
    {
        parent::setUp();
        $this->rules = (new LikeAPIRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /** @test */
    public function pass_valid_user_id()
    {
        $this->assertTrue($this->validateField('user_id', 1));
        $this->assertFalse($this->validateField('user_id', '1'));
        $this->assertTrue($this->validateField('user_id', 10000));
    }

    /** @test */
    public function fail_invalid_user_id()
    {
        $this->assertFalse($this->validateField('user_id', '-1'));
        $this->assertFalse($this->validateField('user_id', -1));
        $this->assertFalse($this->validateField('user_id', .1));
        $this->assertFalse($this->validateField('user_id', 0.1));
        $this->assertFalse($this->validateField('user_id', '2 * 3'));
        $this->assertFalse($this->validateField('user_id', 'test'));
        $this->assertFalse($this->validateField('user_id', 0));
    }

    protected function getFieldValidator($field, $value)
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        );
    }

    protected function validateField($field, $value)
    {
        return $this->getFieldValidator($field, $value)->passes();
    }
}