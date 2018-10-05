<?php


class AvatarTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_instantiated()
    {
        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        $generator = Mockery::mock('Laravolt\Avatar\InitialGenerator');
        $generator->shouldReceive('getInitial')->andReturn('AB');
        $generator->shouldReceive('setUppercase');
        $generator->shouldReceive('setAscii');

        new \Laravolt\Avatar\Avatar([], $cache, $generator);
    }

    /**
     * @test
     */
    public function it_can_override_attributes_when_instantiated()
    {
        $config = [
            'ascii'       => false,
            'shape'       => 'circle',
            'width'       => 100,
            'height'      => 100,
            'chars'       => 2,
            'fontSize'    => 48,
            'fonts'       => ['arial.ttf'],
            'foregrounds' => ['#FFFFFF'],
            'backgrounds' => ['#000000'],
            'border'      => ['size' => 1, 'color' => '#999999'],
        ];

        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        $generator = Mockery::mock('Laravolt\Avatar\InitialGenerator');
        $generator->shouldReceive('getInitial')->andReturn('AB');
        $generator->shouldReceive('setUppercase');
        $generator->shouldReceive('setAscii');

        $avatar = new \Laravolt\Avatar\Avatar($config, $cache, $generator);

        $this->assertAttributeEquals(2, 'chars', $avatar);
        $this->assertAttributeEquals('circle', 'shape', $avatar);
        $this->assertAttributeEquals(100, 'width', $avatar);
        $this->assertAttributeEquals(100, 'height', $avatar);
        $this->assertAttributeEquals(['#000000'], 'availableBackgrounds', $avatar);
        $this->assertAttributeEquals(['#FFFFFF'], 'availableForegrounds', $avatar);
        $this->assertAttributeEquals(['arial.ttf'], 'fonts', $avatar);
        $this->assertAttributeEquals(48, 'fontSize', $avatar);
        $this->assertAttributeEquals(1, 'borderSize', $avatar);
        $this->assertAttributeEquals('#999999', 'borderColor', $avatar);
        $this->assertAttributeEquals(false, 'ascii', $avatar);
    }

    /**
     * @test
     */
    public function it_can_override_attributes_after_set_name()
    {
        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');
        $generator = Mockery::mock('Laravolt\Avatar\InitialGenerator');
        $generator->shouldReceive('setName')->andReturnSelf();
        $generator->shouldReceive('setLength');
        $generator->shouldReceive('getInitial')->andReturn('A');
        $generator->shouldReceive('setUppercase');
        $generator->shouldReceive('setAscii');
        $generator->shouldReceive('base_path');
        $config = ['backgrounds' => ['#000000', '#111111'], 'foregrounds' => ['#EEEEEE', '#FFFFFF']];

        $avatar = new \Laravolt\Avatar\Avatar($config, $cache, $generator);
        $avatar->setFontFolder(['fonts/']);
        $avatar->create('A');

        $this->assertAttributeEquals('#FFFFFF', 'foreground', $avatar);
    }

    /**
     * @test
     */
    public function it_has_correct_random_background()
    {
        $config = [
            'foregrounds' => ['#000000', '#111111'],
            'backgrounds' => ['#111111', '#000000'],
        ];

        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        $generator = Mockery::mock('Laravolt\Avatar\InitialGenerator');
        $generator->shouldReceive('setUppercase');
        $generator->shouldReceive('setAscii');

        $avatar = new \Laravolt\Avatar\Avatar($config, $cache, $generator);
        $avatar->setFontFolder(['fonts/']);

        $name = 'A';

        $generator->shouldReceive('setLength')->andReturn(1);
        $generator->shouldReceive('setName')->andReturn($name);
        $generator->shouldReceive('setUppercase')->andReturnSelf();
        $generator->shouldReceive('getInitial')->andReturn('A');
        $avatar->create($name);

        $this->assertAttributeEquals('#000000', 'background', $avatar);
        $this->assertAttributeEquals('#111111', 'foreground', $avatar);
    }

    /**
     * @test
     */
    public function it_has_different_random_background()
    {
        $config = [
            'backgrounds' => ['#000000', '#111111'],
        ];

        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        $generator = Mockery::mock('Laravolt\Avatar\InitialGenerator');
        $generator->shouldReceive('setUppercase');
        $generator->shouldReceive('setAscii');

        $name1 = 'AA';
        $name2 = 'AAA';

        $generator->shouldReceive('setLength')->andReturn(2);
        $generator->shouldReceive('setName')->andReturn($name1);
        $generator->shouldReceive('getInitial')->andReturn('AA');

        $avatar1 = new \Laravolt\Avatar\Avatar($config, $cache, $generator);
        $avatar1->setFontFolder(['fonts/']);
        $avatar1->create($name1);

        $generator->shouldReceive('setName')->andReturn($name2);

        $avatar2 = new \Laravolt\Avatar\Avatar($config, $cache, $generator);
        $avatar2->setFontFolder(['fonts/']);
        $avatar2->create($name2);

        $this->assertAttributeEquals('#000000', 'background', $avatar1);
        $this->assertAttributeEquals('#111111', 'background', $avatar2);
    }
}
