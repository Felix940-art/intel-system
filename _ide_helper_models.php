<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $frequency
 * @property string|null $datetime_code
 * @property string|null $conversation
 * @property string|null $clarity
 * @property string|null $lob
 * @property string|null $barangay
 * @property string|null $municipality
 * @property string|null $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereBarangay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereClarity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereDatetimeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereLob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereMunicipality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Frequency whereUpdatedAt($value)
 */
	class Frequency extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

