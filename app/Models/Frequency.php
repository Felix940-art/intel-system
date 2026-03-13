<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Frequency extends Model
{
    use HasFactory;

    protected $fillable = [
        'frequency',
        'datetime_code',
        'site_location',
        'conversation',
        'clarity',
        'lob',
        'barangay',
        'municipality',
        'province',
        'is_watchlisted',
        'threat_confronted',
        'user_id',
    ];

    protected $appends = [
        'analysis_summary',
        'confidence_label',
        'threat_color',
        'conversation_summary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAnalysisSummaryAttribute(): string
    {
        $origin = collect([$this->barangay, $this->municipality, $this->province])
            ->filter()
            ->implode(', ');

        $lobText = $this->lob
            ? "at a bearing of {$this->lob}°"
            : "with an undetermined bearing";

        $originText = $origin
            ? "suggesting a probable source near {$origin}"
            : "with no confirmed point of origin";

        $clarityText = match ($this->clarity) {
            '1x1' => 'very poor signal clarity with heavy interference',
            '3x3' => 'moderate clarity with partial intelligibility',
            '5x5' => 'strong and clear reception',
            default => 'an unassessed signal quality',
        };

        $site = $this->site_location ?? 'an unspecified monitoring location';

        $watch = $this->is_watchlisted
            ? 'This frequency has been flagged for continued monitoring.'
            : 'No active watchlist flag is currently assigned.';

        $threat = $this->threat_confronted
            ? "Threat classification identified as {$this->threat_confronted}."
            : 'No immediate threat classification has been assigned.';

        return implode(' ', [
            "This radio signal was intercepted {$lobText}, {$originText}.",
            "The signal exhibited {$clarityText}.",
            "Monitoring operations were conducted from {$site}.",
            $watch,
            $threat,
        ]);
    }

    public function getConfidenceLabelAttribute(): string
    {
        return match (true) {
            $this->clarity === '5x5' && $this->lob && $this->barangay => 'High confidence assessment',
            $this->clarity === '3x3' => 'Moderate confidence assessment',
            default => 'Low confidence assessment',
        };
    }

    public function getThreatColorAttribute(): string
    {
        return match ($this->threat_confronted) {
            'SRC'  => 'text-red-500',
            'SRGU' => 'text-orange-400',
            'SRMA' => 'text-yellow-400',
            'SROC' => 'text-purple-400',
            default => 'text-slate-400',
        };
    }

    public function getConversationSummaryAttribute(): ?string
    {
        if (! $this->conversation) {
            return null;
        }

        return Str::limit(
            preg_replace('/\s+/', ' ', strip_tags($this->conversation)),
            180
        );
    }

    public function getTimelineSummary(): string
    {
        $created = Carbon::parse($this->created_at)->format('F d, Y');
        $updated = Carbon::parse($this->updated_at)->format('F d, Y');

        return $created === $updated
            ? "First detected on {$created}."
            : "First detected on {$created}, last updated on {$updated}.";
    }
}
