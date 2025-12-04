<?php

namespace ArieTimmerman\Laravel\SCIMServer\Http\Controllers;

use Illuminate\Support\Carbon;

class ServiceProviderController extends Controller
{
    private function getAuthenticationSchemes(): array
    {
        $allSchemes = [
            'oauth' => [
                'name' => 'OAuth',
                'description' => 'Authentication scheme using the OAuth Standard',
                'specUri' => 'http://tools.ietf.org/html/rfc5849',
                'documentationUri' => 'http://example.com/help/oauth.html',
                'type' => 'oauth',
            ],
            'oauth2' => [
                'name' => 'OAuth 2.0',
                'description' => 'Authentication scheme using the OAuth 2.0 Standard',
                'specUri' => 'http://tools.ietf.org/html/rfc6749',
                'documentationUri' => 'http://example.com/help/oauth2.html',
                'type' => 'oauth2',
            ],
            'oauthbearertoken' => [
                'name' => 'OAuth Bearer Token',
                'description' => 'Authentication scheme using the OAuth Bearer Token Standard',
                'specUri' => 'http://www.rfc-editor.org/info/rfc6750',
                'documentationUri' => 'http://example.com/help/oauth.html',
                'type' => 'oauthbearertoken',
            ],
            'httpbasic' => [
                'name' => 'HTTP Basic',
                'description' => 'Authentication scheme using the HTTP Basic Standard',
                'specUri' => 'http://www.rfc-editor.org/info/rfc2617',
                'documentationUri' => 'http://example.com/help/httpBasic.html',
                'type' => 'httpbasic',
            ],
            'httpdigest' => [
                'name' => 'HTTP Digest',
                'description' => 'Authentication scheme using the HTTP Digest Standard',
                'specUri' => 'http://www.rfc-editor.org/info/rfc2617',
                'documentationUri' => 'http://example.com/help/httpDigest.html',
                'type' => 'httpdigest',
            ],
        ];

        $schemes = config('scim.authenticationSchemes', ['oauthbearertoken']);
        $authenticationSchemes = [];

        foreach ($schemes as $index => $scheme) {
            if (isset($allSchemes[$scheme])) {
                $authenticationSchemes[] = array_merge(
                    $allSchemes[$scheme],
                    ['primary' => $index === 0]
                );
            }
        }

        return $authenticationSchemes;
    }

    public function index()
    {
        $cursorPaginationEnabled = (bool) config('scim.pagination.cursorPaginationEnabled', true);

        $pagination = [
            "cursor" => $cursorPaginationEnabled,
            "index" => true,
            "defaultPaginationMethod" => "index",
            "defaultPageSize" => config('scim.pagination.defaultPageSize'),
            "maxPageSize" => config('scim.pagination.maxPageSize'),
        ];

        if ($cursorPaginationEnabled) {
            $pagination["cursorTimeout"] = 3600;
        }

        $authenticationSchemes = $this->getAuthenticationSchemes();

        return [
            "schemas" => ["urn:ietf:params:scim:schemas:core:2.0:ServiceProviderConfig"],
            "patch" => [
                "supported" => true,
            ],
            "bulk" => [
                "supported" => true,
                "maxPayloadSize" => BulkController::MAX_PAYLOAD_SIZE,
                "maxOperations" => BulkController::MAX_OPERATIONS
            ],
            "filter" => [
                "supported" => true,
                "maxResults" => 100
            ],
            "changePassword" => [
                "supported" => true,
            ],
            "sort" => [
                "supported" => true,
            ],
            "etag" => [
                "supported" => true,
            ],
            "authenticationSchemes" => $authenticationSchemes,
            "pagination" => $pagination,
            "meta" => [
                "location" => route('scim.serviceproviderconfig'),
                "resourceType" => "ServiceProviderConfig",

                "created" => Carbon::createFromTimestampUTC(filectime(__FILE__))->format('c'),
                "lastModified" => Carbon::createFromTimestampUTC(filemtime(__FILE__))->format('c'),
                "version" => sprintf('W/"%s"', sha1(filemtime(__FILE__))),
            ],
        ];
    }
}
