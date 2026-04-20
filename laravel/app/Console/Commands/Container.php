<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Container extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'nest:container';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Manages docker containers';

	/**
	 * Execute the console command.
	 */
	public function handle() {
		// list
		// dd( $this->listContainers() );

		// // create
		// dump( $this->createContainer( [
		// 	'Image' => 'alpine:latest',
		// 	'Cmd' => [ 'echo', 'Hello World' ],
		// ] ) );

		dd( $this->temp() );
	}

	public function listContainers() {
		$response = Http::get(
			config( 'services.docker_proxy.url' ) . '/containers/json'
		)
			->throw()
			->json();

		return $response;
	}

	public function createContainer( array $config ) {
		$response = Http::post(
			config( 'services.docker_proxy.url' ) . '/containers/create',
			$config
		)
			->throw()
			->json();

		return $response;
	}

	public function startContainer( string $containerId ) {
		$response = Http::post(
			config( 'services.docker_proxy.url' ) . "/containers/{$containerId}/start"
		)
			->throw()
			->json();

		return $response;
	}

	public function temp() {
		$base = 'http://localhost:2375';

		// 1. Create container
		$create = Http::post( "$base/containers/create", [
			'Image' => 'alpine/git',
			'Cmd' => [ '--version' ],
			// 'HostConfig' => [
			// 'AutoRemove' => true,
			// ],
		] );

		if ( ! $create->successful() ) {
			dump( $create->body() );
			return 1;
		}

		$id = $create['Id'];


		// 2. Start container
		Http::post( "$base/containers/{$id}/start" );

		// 3. Wait briefly (dirty but fine for POC)
		sleep( 1 );

		// 4. Get logs
		$logs = Http::get( "$base/containers/{$id}/logs", [
			'stdout' => 'true',
			'stderr' => 'true',
		] );

		dump( $logs->body() );

		$delete = Http::delete( "$base/containers/{$id}", [
			'force' => 'true',
		] );

		dump( $delete->body() );

		return 0;
	}
}
