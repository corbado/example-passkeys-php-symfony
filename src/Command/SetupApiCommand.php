<?php

namespace App\Command;

use App\Exception\ApiErrorException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:setup-api',
    description: 'Setup api in order to run this widget',
)]
class SetupApiCommand extends Command
{
    private string $projectID;
    private string $apiSecret;
    private string $webhookUsername;
    private string $webhookPassword;

    public function __construct(string $projectID, string $apiSecret, string $webhookUsername, string $webhookPassword, string $name = null)
    {
        parent::__construct($name);
        $this->apiSecret = $apiSecret;
        $this->projectID = $projectID;
        $this->webhookUsername = $webhookUsername;
        $this->webhookPassword = $webhookPassword;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectConfig = [
            "externalName" => "Local Test",
            "emailFrom" => "localtest@corbado.com",
            "smsFrom" => "Corbado Localtest",
            "externalApplicationProtocolVersion" => "v2",
            "webhookUsername" => $this->webhookUsername,
            "webhookPassword" => $this->webhookPassword,
            "applicationUrl" => "http://localhost:8000/login",
            "webhookURL" => "http://localhost:8000/corbado-webhook",
            "authSuccessRedirectUrl" => "http://localhost:8000/api/sessionToken",
            "allowUserRegistration" => true,
            "allowIPStickiness" => false,
        ];

        $this->doApiCall('/v1/projectConfig', $projectConfig);

        $webauthnSettings = [
            "name" => "Localhost",
            "origin" => "http://localhost:8000",
        ];

        try {
            $this->doApiCall('/v1/webauthn/settings', $webauthnSettings);
        } catch (ApiErrorException $e) {

            if (!isset($e->getApiResponse()['error']['validation']) || $e->getApiResponse()['error']['validation'][0]['message'] !== 'already exists') {
                throw $e;
            }

        }

        $output->writeln("Configured api");

        return Command::SUCCESS;
    }

    private function doApiCall(string $endpoint, array $payload)
    {
        $ch = curl_init('https://api.corbado.com' . $endpoint);

        curl_setopt($ch, CURLOPT_USERPWD, $this->projectID . ':' . $this->apiSecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($responseCode !== 200) {
            curl_close($ch);
            throw new ApiErrorException('API call ' . $endpoint . ' response is not 200, got ' . $responseCode . ' with response ' . $result, json_decode($result, true));
        }

        curl_close($ch);
    }
}
