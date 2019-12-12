<?php

namespace App;

class Filesystem {
    public function write(string $level, string $message): void {
        file_put_contents('app.log', sprintf("[%s] %s", $level, $message));
    }
}