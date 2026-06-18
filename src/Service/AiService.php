<?php

namespace App\Service;

/**
 * Service for interacting with Ollama AI running locally on Mac.
 * Used to generate content suggestions for quotes.
 */
class AiService
{
    public function __construct(private string $ollamaUrl, private string $model = 'llama3.2') {}

    /**
     * Generates a professional service description for a quote line.
     * Takes a short keyword or title and returns a detailed description.
     */
    public function generateQuoteLineDescription(string $keyword): string
    {
        $prompt = "You are a professional freelance developer assistant. 
            Generate a short, professional description (2-3 sentences) for a quote line item based on this keyword: '{$keyword}'.
            The description should be suitable for a client-facing quote document.
            Reply only with the description, no preamble.";

        $response = $this->sendRequest($prompt);

        return $response;
    }

    /**
     * Sends a request to the Ollama API and returns the generated text.
     */
    private function sendRequest(string $prompt): string
    {
        $data = json_encode([
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        $ch = curl_init($this->ollamaUrl . '/api/generate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($result, true);

        return $decoded['response'] ?? 'Error generating description';
    }
}
