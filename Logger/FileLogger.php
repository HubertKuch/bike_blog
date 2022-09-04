<?php

namespace Hubert\BikeBlog\Logger;

class FileLogger implements Logger {

    public function __construct(
        private readonly string $path
    ) {
    }

    public function log(mixed $data, string $prefix): void {
        $jsonData = json_encode($data);
        $fileName = $this->getFileName($prefix);

        file_put_contents($fileName, $jsonData);
    }

    private function getFileName(string $prefix): string {
        return $this->path . $prefix . time() . ".log";
    }
}
