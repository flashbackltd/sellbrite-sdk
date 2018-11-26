<?php

declare(strict_types=1);

namespace TrollAndToad\Sellbrite\Test\Warehouses;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TrollAndToad\Sellbrite\Warehouses\GetWarehouses;

class GetWarehousesTest extends TestCase
{
    public function testGetWarehouseApiRequest()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                200,
                [ 'Content-Type' => 'application/json' ],
                '[
                    {
                        "uuid": "test-uuid-7891-1234",
                        "name": "Local Warehouse",
                        "inventory_master": "Sellbrite",
                        "address_1": "1 Octopus Way",
                        "address_2":  "Apt. A",
                        "city": "Alhambra",
                        "state_region": "CA",
                        "postal_code": "91801",
                        "country_code": "US",
                        "archived": false
                    }
                ]'
            )
        );

        // Instantiate a new GetWarehouses API Object
        $getWarehouses = new GetWarehouses($accountToken, $secretKey, $mockClient);

        // Get the JSON response from the request
        $jsonResponse = $getWarehouses->sendRequest();

        // Assert the returned JSON response matches the expected data
        $this->assertJsonStringEqualsJsonString(
            $jsonResponse,
            json_encode([
                [
                    'uuid'             => 'test-uuid-7891-1234',
                    'name'             => 'Local Warehouse',
                    'inventory_master' => 'Sellbrite',
                    'address_1'        => '1 Octopus Way',
                    'address_2'        =>  'Apt. A',
                    'city'             => 'Alhambra',
                    'state_region'     => 'CA',
                    'postal_code'      => '91801',
                    'country_code'     => 'US',
                    'archived'         => false
                ]
            ])
        );
    } // End public function testGetWarehouseApiRequest

    public function testBadCredentialsForWarehouseApiRequestShouldReturnAnException()
    {
        // Get the stored credentials
        $accountToken = '';
        $secretKey    = '';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining an error
        $mockClient->shouldReceive('request')
            ->andReturns(new \GuzzleHttp\Psr7\Response(
                401,
                [ 'Content-Type' => 'application/json' ],
                "You couldn't be authenticated")
            );

        // Instantiate a new GetWarehouses API Object
        $getWarehouses = new GetWarehouses($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request and store the response
        $json = $getWarehouses->sendRequest();
    } // End public function testBadCredentialsForWarehouseApiRequestShouldReturnAnException

    public function testBadCredentialsForWarehouseApiRequestShouldReturnDefaultException()
    {
        // Get the stored credentials
        $accountToken = '';
        $secretKey    = '';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining an error
        $mockClient->shouldReceive('request')
            ->andReturns(new \GuzzleHttp\Psr7\Response(
                400,
                [ 'Content-Type' => 'application/json' ],
                "You couldn't be authenticated")
            );

        // Instantiate a new GetWarehouses API Object
        $getWarehouses = new GetWarehouses($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request and store the response
        $jsonResponse = $getWarehouses->sendRequest();
    } // End public function testBadCredentialsForWarehouseApiRequestShouldReturnDefaultException
} // End class GetWarehouseTest
