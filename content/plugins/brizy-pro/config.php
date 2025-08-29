<?php

class BrizyPro_Config {
	const ACTIVATE_LICENSE = 'https://www.brizy.io/account/misc/brizy-license/';
	const UDPATE_LICENSE = 'https://www.brizy.io/account/misc/brizy-license/update';
	const DEACTIVATE_LICENSE = 'https://www.brizy.io/account/misc/brizy-license/deactivate';

	const BRIZY_APPLICATION_INTEGRATION_URL = 'https://forms.brizy.io';

	const COMPILER_DOWNLOAD_URL = 'https://static.brizy.io/builds/pro/' . BRIZY_PRO_EDITOR_VERSION;

	const AI_URL = 'https://admin.brizy.io/openai/wp';

	static public function getEditorBuildUrl() {
		return BRIZY_PRO_PLUGIN_URL . '/public/editor-build/prod';
	}

	static public function getLicenseActivationData() {
		$data = array(
			'market'   => 'brizy',
			'author'   => 'brizy',
			'theme_id' => '000000',
		);

		return apply_filters( 'brizy-pro-license-data', $data );
	}

	static public function getConfigUrls() {

		return 'public/editor-build/prod';
	}

    static public function getCompilerDownloadUrl() {
        return 'https://static.brizy.io/builds/pro/' . BRIZY_PRO_EDITOR_VERSION;
    }
}
