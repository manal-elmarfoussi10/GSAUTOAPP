<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence,
            'sender_name' => $this->faker->name,
            'recipient_name' => 'You',
            'content' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'tag' => $this->faker->boolean(25) ? 'important' : null,
            'label_color' => $this->faker->boolean(25) ? '#facc15' : null, // Yellow
            'is_deleted' => false,
            'is_read' => $this->faker->boolean(70),
            'file_path' => $this->faker->boolean(40) ? 'attachments/sample.pdf' : null,
            'file_name' => $this->faker->boolean(40) ? 'sample.pdf' : null,
            'created_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
