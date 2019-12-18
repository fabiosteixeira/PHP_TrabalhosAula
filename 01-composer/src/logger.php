<?php

class Logger {

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem){
        $this->filesystem = $filesystem;
    }

    public function info(string $message): void {
        $this->filesystem->write('INFO', $message);
    }
}