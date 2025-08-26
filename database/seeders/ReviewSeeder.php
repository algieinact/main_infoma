<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users, residences, and activities
        $users = User::where('role', 'user')->take(5)->get();
        $residences = Residence::take(3)->get();
        $activities = Activity::take(3)->get();

        if ($users->isEmpty() || $residences->isEmpty() || $activities->isEmpty()) {
            $this->command->info('Skipping ReviewSeeder: Not enough users, residences, or activities found.');
            return;
        }

        // Create reviews for residences
        foreach ($residences as $residence) {
            foreach ($users->take(3) as $user) {
                Review::create([
                    'user_id' => $user->id,
                    'reviewable_type' => Residence::class,
                    'reviewable_id' => $residence->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->getRandomComment('residence'),
                    'is_anonymous' => rand(0, 1),
                ]);
            }
        }

        // Create reviews for activities
        foreach ($activities as $activity) {
            foreach ($users->take(3) as $user) {
                Review::create([
                    'user_id' => $user->id,
                    'reviewable_type' => Activity::class,
                    'reviewable_id' => $activity->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->getRandomComment('activity'),
                    'is_anonymous' => rand(0, 1),
                ]);
            }
        }

        // Update ratings for residences and activities
        $this->updateRatings();
    }

    private function getRandomComment(string $type): string
    {
        $residenceComments = [
            'Tempat yang sangat nyaman dan bersih!',
            'Lokasi strategis, dekat dengan fasilitas umum.',
            'Harga terjangkau untuk kualitas yang diberikan.',
            'Pemilik sangat ramah dan responsif.',
            'Fasilitas lengkap sesuai dengan yang dijanjikan.'
        ];

        $activityComments = [
            'Kegiatan yang sangat bermanfaat dan informatif!',
            'Pembicara sangat kompeten dan materi mudah dipahami.',
            'Organisasi acara yang sangat baik dan terstruktur.',
            'Worth it untuk harga yang dibayarkan.',
            'Akan mengikuti kegiatan serupa di masa depan.'
        ];

        return $type === 'residence' 
            ? $residenceComments[array_rand($residenceComments)]
            : $activityComments[array_rand($activityComments)];
    }

    private function updateRatings(): void
    {
        // Update residence ratings
        $residences = Residence::all();
        foreach ($residences as $residence) {
            $avgRating = $residence->reviews()->avg('rating') ?? 0;
            $totalReviews = $residence->reviews()->count();
            
            $residence->update([
                'rating' => round($avgRating, 2),
                'total_reviews' => $totalReviews
            ]);
        }

        // Update activity ratings
        $activities = Activity::all();
        foreach ($activities as $activity) {
            $avgRating = $activity->reviews()->avg('rating') ?? 0;
            $totalReviews = $activity->reviews()->count();
            
            $activity->update([
                'rating' => round($avgRating, 2),
                'total_reviews' => $totalReviews
            ]);
        }
    }
}
