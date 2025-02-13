<?php

namespace App\Service;

final class HomeService {
    public function getDescription(): string
    {
        // Returning mock text as a description
        return "Welcome to the home page! This is a mock description for testing purposes.";
    }
}