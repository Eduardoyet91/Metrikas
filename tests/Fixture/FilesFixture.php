<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FilesFixture
 */
class FilesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'created_at' => 1719263290,
                'user_id' => 1,
                'path' => 'Lorem ipsum dolor sit amet',
                'type' => 1,
            ],
        ];
        parent::init();
    }
}
