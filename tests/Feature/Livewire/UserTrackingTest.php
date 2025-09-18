<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('user-tracking');

    $component->assertSee('');
});
