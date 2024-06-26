<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $date
 * @property string|null $type
 * @property int|null $amount
 * @property int|null $previous_amount
 * @property int|null $order_id
 * @property int|null $guide
 * @property string|null $Description
 *
 * @property \App\Model\Entity\Order $order
 */
class Transaction extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => true,
        'date' => true,
        'type' => true,
        'amount' => true,
        'previous_amount' => true,
        'order_id' => true,
        'guide' => true,
        'description' => true,
        'order' => true,
    ];
}
