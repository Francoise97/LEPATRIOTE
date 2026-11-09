<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'model_type',
        'model_id',
        'collection_name',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function publicUrl(): string
    {
        return $this->file_type === 'video/external'
            ? $this->file_path
            : asset('storage/'.$this->file_path);
    }

    public function videoEmbedUrl(): ?string
    {
        if ($this->file_type !== 'video/external') {
            return null;
        }

        $parts = parse_url($this->file_path);
        if (! is_array($parts)) {
            return null;
        }
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true)) {
            parse_str($parts['query'] ?? '', $query);
            $videoId = ($host === 'youtu.be' ? trim($path, '/') : ($query['v'] ?? null))
                ?? (preg_match('~/(?:embed|shorts)/([A-Za-z0-9_-]+)~', $path, $matches) ? $matches[1] : null);

            return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId)
                ? 'https://www.youtube-nocookie.com/embed/'.$videoId
                : null;
        }

        if (in_array($host, ['vimeo.com', 'www.vimeo.com'], true) && preg_match('~^/(\d+)/?$~', $path, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return null;
    }
}
