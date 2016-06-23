<?php

namespace Phalcon\Test\Unit\Http;

use Phalcon\Test\Unit\Http\Helper\HttpBase;
use Phalcon\Test\Proxy\Http\Request;
use Phalcon\DiInterface;

/**
 * \Phalcon\Test\Unit\Http\RequestTest
 * Tests the \Phalcon\Http\Request component
 *
 * @copyright (c) 2011-2016 Phalcon Team
 * @link      http://www.phalconphp.com
 * @author    Andres Gutierrez <andres@phalconphp.com>
 * @author    Nikolaos Dimopoulos <nikos@phalconphp.com>
 * @package   Phalcon\Test\Unit\Http
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file docs/LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalconphp.com
 * so that we can send you a copy immediately.
 */
class RequestTest extends HttpBase
{
    /**
     * Tests the getDI
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-23
     */
    public function testHttpRequestGetDI()
    {
        $request = $this->getRequestObject();

        expect($request->getDI() instanceof DiInterface)->true();
    }

    /**
     * Tests the instance of the object
     */
    public function testHttpRequestInstanceOf()
    {
        $this->specify(
            "The new object is not the correct class",
            function () {
                expect($this->getRequestObject() instanceof Request)->true();
            }
        );
    }

    /**
     * Tests getHeader empty
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestHeaderGetEmpty()
    {
        $this->specify(
            "Empty header does not contain correct data",
            function () {
                $request = $this->getRequestObject();

                expect($request->getHeader('LOL'))->isEmpty();
            }
        );
    }

    /**
     * Tests getHeader
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestHeaderGet()
    {
        $this->specify(
            "Empty header does not contain correct data",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTP_LOL', 'zup');
                $actual = $request->getHeader('LOL');
                $this->unsetServerVar('HTTP_LOL');

                expect($actual)->equals('zup');
            }
        );
    }

    /**
     * Tests getHeader
     *
     * @issue  2294
     * @author Serghei Iakovlev <nikos@phalconphp.com>
     * @since  2016-10-19
     */
    public function testHttpRequestCustomHeaderGet()
    {
        $this->specify(
            "getHeaders does not returns correct header values",
            function () {
                $_SERVER['HTTP_FOO'] = 'Bar';
                $_SERVER['HTTP_BLA_BLA'] = 'boo';
                $_SERVER['HTTP_AUTH'] = true;

                $request = $this->getRequestObject();

                expect($request->getHeaders())->equals(['Foo' => 'Bar', 'Bla-Bla' => 'boo', 'Auth' => 1]);
            }
        );
    }

    /**
     * Tests isAjax default
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestIsAjaxDefault()
    {
        $this->specify(
            "Default request is Ajax",
            function () {
                $request = $this->getRequestObject();

                expect($request->isAjax())->false();
            }
        );
    }

    /**
     * Tests isAjax
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestIsAjax()
    {
        $this->specify(
            "Request is not Ajax",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
                $actual = $request->isAjax();
                $this->unsetServerVar('HTTP_X_REQUESTED_WITH');

                expect($actual)->true();
            }
        );
    }

    /**
     * Tests getScheme default
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestGetSchemeDefault()
    {
        $this->specify(
            "Default scheme is not http",
            function () {
                $request = $this->getRequestObject();

                expect($request->getScheme())->equals('http');
            }
        );
    }

    /**
     * Tests getScheme with HTTPS
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestGetScheme()
    {
        $this->specify(
            "Scheme is not https",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTPS', 'on');
                $actual = $request->getScheme();
                $this->unsetServerVar('HTTPS');

                expect($actual)->equals('https');
            }
        );
    }

    /**
     * Tests isSecureRequest default
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestIsSecureRequestDefault()
    {
        $this->specify(
            "Default isSecureRequest is true",
            function () {
                $request = $this->getRequestObject();

                expect($request->isSecureRequest())->false();
            }
        );
    }

    /**
     * Tests isSecureRequest
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestIsSecureRequest()
    {
        $this->specify(
            "isSecureRequest is not true",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTPS', 'on');
                $actual = $request->isSecureRequest();
                $this->unsetServerVar('HTTPS');

                expect($actual)->true();
            }
        );
    }

    /**
     * Tests isSoapRequested default
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-23
     */
    public function testHttpRequestIsSoapRequestedDefault()
    {
        $this->specify(
            "Default isSoapRequest is true",
            function () {
                $request = $this->getRequestObject();

                expect($request->isSoapRequested())->false();
            }
        );
    }

    /**
     * Tests isSoapRequest
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestIsSoapRequested()
    {
        $this->specify(
            "isSoapRequest is not true",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('CONTENT_TYPE', 'application/soap+xml');
                $actual = $request->isSoapRequested();
                $this->unsetServerVar('CONTENT_TYPE');

                expect($actual)->true();
            }
        );
    }

    /**
     * Tests getServerAddress default
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddressDefault()
    {
        $this->specify(
            "default server address is not 127.0.0.1",
            function () {
                $request = $this->getRequestObject();

                expect($request->getServerAddress())->equals('127.0.0.1');

            }
        );
    }

    /**
     * Tests getServerAddress
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddress()
    {
        $this->specify(
            "server address does not contain correct IP",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('SERVER_ADDR', '192.168.4.1');
                $actual = $request->getServerAddress();
                $this->unsetServerVar('SERVER_ADDR');

                expect($actual)->equals('192.168.4.1');
            }
        );
    }

    /**
     * Tests getHttpHost
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestHttpHost()
    {
        $this->specify(
            "http host without http does not contain correct data",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTPS', 'off');
                $this->setServerVar('SERVER_NAME', 'localhost');
                $this->setServerVar('SERVER_PORT', 80);

                expect($request->getHttpHost())->equals('localhost');
            }
        );

        $this->specify(
            "http host with http does not contain correct data",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTPS', 'on');
                $this->setServerVar('SERVER_NAME', 'localhost');
                $this->setServerVar('SERVER_PORT', 80);

                expect($request->getHttpHost())->equals('localhost:80');
            }
        );

        $this->specify(
            "http host with https and 443 port does not contain correct data",
            function () {
                $request = $this->getRequestObject();
                $this->setServerVar('HTTPS', 'on');
                $this->setServerVar('SERVER_NAME', 'localhost');
                $this->setServerVar('SERVER_PORT', 443);

                expect($request->getHttpHost())->equals('localhost');
            }
        );
    }

    /**
     * Tests POST functions
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestInputPost()
    {
        $this->specify(
            "hasPost for empty element returns incorrect results",
            function () {
                $this->hasEmpty('hasPost');
            }
        );

        $this->specify(
            "hasPost for set element returns incorrect results",
            function () {
                $this->hasNotEmpty('hasPost', 'setPostVar');
            }
        );

        $this->specify(
            "getPost for empty element returns incorrect results",
            function () {
                $this->getEmpty('getPost');
            }
        );

        $this->specify(
            "getPost returns incorrect results",
            function () {
                $this->getNotEmpty('getPost', 'setPostVar');
            }
        );

        $this->specify(
            "getPost does not return sanitized data",
            function () {
                $this->getSanitized('getPost', 'setPostVar');
            }
        );

        $this->specify(
            "getPost with array as filter does not return sanitized data",
            function () {
                $this->getSanitizedArrayFilter('getPost', ['string'], 'setPostVar');
            }
        );
    }

    /**
     * Tests GET functions
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestInputGet()
    {
        $this->specify(
            "hasQuery for empty element returns incorrect results",
            function () {
                $this->hasEmpty('hasQuery');
            }
        );

        $this->specify(
            "hasQuery for set element returns incorrect results",
            function () {
                $this->hasNotEmpty('hasQuery', 'setGetVar');
            }
        );

        $this->specify(
            "getQuery for empty element returns incorrect results",
            function () {
                $this->getEmpty('getQuery');
            }
        );

        $this->specify(
            "getQuery returns incorrect results",
            function () {
                $this->getNotEmpty('getQuery', 'setGetVar');
            }
        );

        $this->specify(
            "getQuery does not return sanitized data",
            function () {
                $this->getSanitized('getQuery', 'setGetVar');
            }
        );

        $this->specify(
            "getQuery with array as filter does not return sanitized data",
            function () {
                $this->getSanitizedArrayFilter('getQuery', ['string'], 'setGetVar');
            }
        );
    }

    /**
     * Tests REQUEST functions
     *
     * @author Nikolaos Dimopoulos <nikos@phalconphp.com>
     * @since  2014-10-04
     */
    public function testHttpRequestInputRequest()
    {
        $this->specify(
            "has for empty element returns incorrect results",
            function () {
                $this->hasEmpty('has');
            }
        );

        $this->specify(
            "has for set element returns incorrect results",
            function () {
                $this->hasNotEmpty('has', 'setRequestVar');
            }
        );

        $this->specify(
            "get for empty element returns incorrect results",
            function () {
                $this->getEmpty('get');
            }
        );

        $this->specify(
            "get returns incorrect results",
            function () {
                $this->getNotEmpty('get', 'setRequestVar');
            }
        );

        $this->specify(
            "get does not return sanitized data",
            function () {
                $this->getSanitized('get', 'setRequestVar');
            }
        );

        $this->specify(
            "get with array as filter does not return sanitized data",
            function () {
                $this->getSanitizedArrayFilter('get', ['string'], 'setRequestVar');
            }
        );
    }

    public function testHttpRequestMethod()
    {
        $request = $this->getRequestObject();

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals($request->getMethod(), 'POST');
        $this->assertTrue($request->isPost());
        $this->assertFalse($request->isGet());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals($request->getMethod(), 'GET');
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());

        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals($request->getMethod(), 'PUT');
        $this->assertTrue($request->isPut());

        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals($request->getMethod(), 'DELETE');
        $this->assertTrue($request->isDelete());

        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $this->assertEquals($request->getMethod(), 'OPTIONS');
        $this->assertTrue($request->isOptions());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($request->isMethod('POST'));
        $this->assertTrue($request->isMethod(['GET', 'POST']));

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($request->isMethod('GET'));
        $this->assertTrue($request->isMethod(['GET', 'POST']));

        $_SERVER['REQUEST_METHOD'] = 'CONNECT';
        $this->assertEquals($request->getMethod(), 'CONNECT');
        $this->assertTrue($request->isConnect());
        $this->assertFalse($request->isGet());

        $_SERVER['REQUEST_METHOD'] = 'TRACE';
        $this->assertEquals($request->getMethod(), 'TRACE');
        $this->assertTrue($request->isTrace());
        $this->assertFalse($request->isGet());

        $_SERVER['REQUEST_METHOD'] = 'PURGE';
        $this->assertEquals($request->getMethod(), 'PURGE');
        $this->assertTrue($request->isPurge());
        $this->assertFalse($request->isGet());
    }

    public function testHttpRequestContentType()
    {
        $request = $this->getRequestObject();

        $this->setServerVar('CONTENT_TYPE', 'application/xhtml+xml');
        $contentType = $request->getContentType();
        $this->assertEquals($contentType, 'application/xhtml+xml');
        $this->unsetServerVar('CONTENT_TYPE');

        $this->setServerVar('HTTP_CONTENT_TYPE', 'application/xhtml+xml');
        $contentType = $request->getContentType();
        $this->assertEquals($contentType, 'application/xhtml+xml');
        $this->unsetServerVar('HTTP_CONTENT_TYPE');
    }

    public function testHttpRequestAcceptableContent()
    {
        $request = $this->getRequestObject();

        $_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8,application/json; level=2; q=0.7';
        $accept = $request->getAcceptableContent();
        $this->assertEquals(count($accept), 5);

        $firstAccept = $accept[0];
        $this->assertEquals($firstAccept['accept'], 'text/html');
        $this->assertEquals($firstAccept['quality'], 1);

        $fourthAccept = $accept[3];
        $this->assertEquals($fourthAccept['accept'], '*/*');
        $this->assertEquals($fourthAccept['quality'], 0.8);

        $lastAccept = $accept[4];
        $this->assertEquals($lastAccept['accept'], 'application/json');
        $this->assertEquals($lastAccept['quality'], 0.7);
        $this->assertEquals($lastAccept['level'], 2);

        $this->assertEquals($request->getBestAccept(), 'text/html');

    }

    public function testHttpRequestAcceptableCharsets()
    {
        $request = $this->getRequestObject();

        $_SERVER['HTTP_ACCEPT_CHARSET'] = 'iso-8859-5,unicode-1-1;q=0.8';
        $accept = $request->getClientCharsets();
        $this->assertEquals(count($accept), 2);

        $firstAccept = $accept[0];
        $this->assertEquals($firstAccept['charset'], 'iso-8859-5');
        $this->assertEquals($firstAccept['quality'], 1);

        $lastAccept = $accept[1];
        $this->assertEquals($lastAccept['charset'], 'unicode-1-1');
        $this->assertEquals($lastAccept['quality'], 0.8);

        $this->assertEquals($request->getBestCharset(), 'iso-8859-5');

    }

    public function testHttpRequestAcceptableLanguage()
    {
        $request = $this->getRequestObject();

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es,es-ar;q=0.8,en;q=0.5,en-us;q=0.3,de-de; q=0.9';
        $accept = $request->getLanguages();
        $this->assertEquals(count($accept), 5);

        $firstAccept = $accept[0];
        $this->assertEquals($firstAccept['language'], 'es');
        $this->assertEquals($firstAccept['quality'], 1);

        $fourthAccept = $accept[3];
        $this->assertEquals($fourthAccept['language'], 'en-us');
        $this->assertEquals($fourthAccept['quality'], 0.3);

        $lastAccept = $accept[4];
        $this->assertEquals($lastAccept['language'], 'de-de');
        $this->assertEquals($lastAccept['quality'], 0.9);

        $this->assertEquals($request->getBestLanguage(), 'es');

    }

    public function testHttpRequestClientAddress()
    {
        $request = $this->getRequestObject();

        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.7.21';
        $this->assertEquals($request->getClientAddress(), '192.168.0.1');
        $this->assertEquals($request->getClientAddress(true), '192.168.7.21');

        $_SERVER['REMOTE_ADDR'] = '86.45.89.47, 214.55.34.56';
        $this->assertEquals($request->getClientAddress(), '86.45.89.47');
    }

    /**
     * @issue 1265
     */
    public function testRequestGetValueByUsingSeveralMethods()
    {
        $request = $this->getRequestObject();

        $_REQUEST = $_GET = $_POST = [
            'string' => 'hello',
            'array' => ['string' => 'world']
        ];

        // get
        $this->assertEquals('hello', $request->get('string', 'string'));
        $this->assertEquals('hello', $request->get('string', 'string', null, true, true));

        $this->assertEquals(['string' => 'world'], $request->get('array', 'string'));
        $this->assertEquals(null, $request->get('array', 'string', null, true, true));

        // getQuery
        $this->assertEquals('hello', $request->getQuery('string', 'string'));
        $this->assertEquals('hello', $request->getQuery('string', 'string', null, true, true));

        $this->assertEquals(['string' => 'world'], $request->getQuery('array', 'string'));
        $this->assertEquals(null, $request->getQuery('array', 'string', null, true, true));

        // getPost
        $this->assertEquals('hello', $request->getPost('string', 'string'));
        $this->assertEquals('hello', $request->getPost('string', 'string', null, true, true));

        $this->assertEquals(['string' => 'world'], $request->getPost('array', 'string'));
        $this->assertEquals(null, $request->getPost('array', 'string', null, true, true));
    }

    public function testRequestGetQuery()
    {
        $_REQUEST = $_GET = $_POST = [
            'id'    => 1,
            'num'   => 'a1a',
            'age'   => 'aa',
            'phone' => ''
        ];

        $this->specify(
            "Request::getQuery does not return correct result",
            function ($function) {
                $request = $this->getRequestObject();

                expect($request->$function('id', 'int', 100))->equals(1);
                expect($request->$function('num', 'int', 100))->equals(1);
                expect($request->$function('age', 'int', 100))->isEmpty();
                expect($request->$function('phone', 'int', 100))->isEmpty();
                expect($request->$function('phone', 'int', 100, true))->equals(100);
            },
            ['examples' => [
                ['get', 'getQuery', 'getPost']
            ]]
        );
    }

    /**
     * Tests Request::hasFiles
     *
     * @author Serghei Iakovelv <serghei@phalconphp.com>
     * @since  2016-01-31
     */
    public function testRequestHasFiles()
    {
        $this->specify(
            "Request::hasFiles does not return correct result",
            function ($files, $all, $onlySuccessful) {
                $request = $this->getRequestObject();

                $_FILES = $files;

                expect($request->hasFiles(false))->equals($all);
                expect($request->hasFiles(true))->equals($onlySuccessful);
            },
            ['examples' => $this->filesProvider()]
        );
    }

    /**
     * Tests uploaded files
     *
     * @author Serghei Iakovelv <serghei@phalconphp.com>
     * @since  2016-01-31
     */
    public function testGetUploadedFiles()
    {
        $this->specify(
            "Request does not handle uploaded files correctly",
            function () {
                $request = $this->getRequestObject();

                $_FILES = [
                    'photo' => [
                        'name'     => ['f0',         'f1',       ['f2',        'f3'],        [[[['f4']]]]],
                        'type'     => ['text/plain', 'text/csv', ['image/png', 'image/gif'], [[[['application/octet-stream']]]]],
                        'tmp_name' => ['t0',         't1',       ['t2',        't3'],        [[[['t4']]]]],
                        'error'    => [0,            0,          [0,           0],           [[[[8]]]]],
                        'size'     => [10,           20,         [30,          40],          [[[[50]]]]],
                    ],
                ];

                $all        = $request->getUploadedFiles(false);
                $successful = $request->getUploadedFiles(true);

                expect($all)->count(5);
                expect($successful)->count(4);

                for ($i=0; $i<=4; ++$i) {
                    expect($all[$i]->isUploadedFile())->false();
                }

                $data = ['photo.0', 'photo.1', 'photo.2.0', 'photo.2.1', 'photo.3.0.0.0.0'];
                for ($i=0; $i<=4; ++$i) {
                    expect($all[$i]->getKey())->equals($data[$i]);
                }

                expect($all[0]->getName())->equals('f0');
                expect($all[1]->getName())->equals('f1');
                expect($all[2]->getName())->equals('f2');
                expect($all[3]->getName())->equals('f3');
                expect($all[4]->getName())->equals('f4');

                expect($all[0]->getTempName())->equals('t0');
                expect($all[1]->getTempName())->equals('t1');
                expect($all[2]->getTempName())->equals('t2');
                expect($all[3]->getTempName())->equals('t3');
                expect($all[4]->getTempName())->equals('t4');

                expect($successful[0]->getName())->equals('f0');
                expect($successful[1]->getName())->equals('f1');
                expect($successful[2]->getName())->equals('f2');
                expect($successful[3]->getName())->equals('f3');

                expect($successful[0]->getTempName())->equals('t0');
                expect($successful[1]->getTempName())->equals('t1');
                expect($successful[2]->getTempName())->equals('t2');
                expect($successful[3]->getTempName())->equals('t3');
            }
        );
    }

    public function testGetAuth()
    {
        $this->specify(
            "Request does not handle auth correctly",
            function ($server, $function, $expected) {
                $request = $this->getRequestObject();

                $_SERVER = $server;

                expect($request->$function())->equals($expected);
            },
            ['examples' => $this->serverProvider()]
        );
    }

    protected function serverProvider()
    {
        return [
            [
                [
                    'PHP_AUTH_USER'	=> 'myleft',
                    'PHP_AUTH_PW'	=> '123456'
                ],
                'getBasicAuth',
                [
                    'username' => 'myleft', 'password' => '123456'
                ]
            ],
            [
                [
                    'PHP_AUTH_DIGEST' => 'Digest username="myleft", realm="myleft", qop="auth", algorithm="MD5", uri="http://localhost:81/", nonce="nonce", nc=nc, cnonce="cnonce", opaque="opaque", response="response"'
                ],
                'getDigestAuth',
                [
                    'username' => 'myleft', 'realm' => 'myleft', 'qop' => 'auth', 'algorithm' => 'MD5', 'uri' => 'http://localhost:81/', 'nonce' => 'nonce', 'nc' => 'nc', 'cnonce' => 'cnonce', 'opaque' => 'opaque', 'response' => 'response'
                ]
            ],
            [
                [
                    'PHP_AUTH_DIGEST' => 'Digest username=myleft, realm=myleft, qop=auth, algorithm=MD5, uri=http://localhost:81/, nonce=nonce, nc=nc, cnonce=cnonce, opaque=opaque, response=response'
                ],
                'getDigestAuth',
                [
                    'username' => 'myleft', 'realm' => 'myleft', 'qop' => 'auth', 'algorithm' => 'MD5', 'uri' => 'http://localhost:81/', 'nonce' => 'nonce', 'nc' => 'nc', 'cnonce' => 'cnonce', 'opaque' => 'opaque', 'response' => 'response'
                ]
            ],
            [
                [
                    'PHP_AUTH_DIGEST' => 'Digest username=myleft realm=myleft qop=auth algorithm=MD5 uri=http://localhost:81/ nonce=nonce nc=nc cnonce=cnonce opaque=opaque response=response'
                ],
                'getDigestAuth',
                [
                    'username' => 'myleft', 'realm' => 'myleft', 'qop' => 'auth', 'algorithm' => 'MD5', 'uri' => 'http://localhost:81/', 'nonce' => 'nonce', 'nc' => 'nc', 'cnonce' => 'cnonce', 'opaque' => 'opaque', 'response' => 'response'
                ]
            ],
        ];
    }

    protected function filesProvider()
    {
        return [
            [
                [
                    'test' => [
                        'name'     => 'name',
                        'type'     => 'text/plain',
                        'size'     => 1,
                        'tmp_name' => 'tmp_name',
                        'error'    => 0,
                    ]
                ],
                1,
                1,
            ],
            [
                [
                    'test' => [
                        'name'     => ['name1', 'name2'],
                        'type'     => ['text/plain', 'text/plain'],
                        'size'     => [1, 1],
                        'tmp_name' => ['tmp_name1', 'tmp_name2'],
                        'error'    => [0, 0],
                    ]
                ],
                2,
                2,
            ],
            [
                [
                    'photo' => [
                        'name' => [
                            0 => '',
                            1 => '',
                            2 => [0 => '', 1 => '', 2 => ''],
                            3 => [0 => ''],
                            4 => [0 => [0 => '']],
                            5 => [0 => [0 => [0 => [0 => '']]]],
                        ],
                        'type' => [
                            0 => '',
                            1 => '',
                            2 => [0 => '', 1 => '', 2 => ''],
                            3 => [0 => ''],
                            4 => [0 => [0 => '']],
                            5 => [0 => [0 => [0 => [0 => '']]]],
                        ],
                        'tmp_name' => [
                            0 => '',
                            1 => '',
                            2 => [0 => '', 1 => '', 2 => ''],
                            3 => [0 => ''],
                            4 => [0 => [0 => '']],
                            5 => [0 => [0 => [0 => [0 => '']]]],
                        ],
                        'error' => [
                            0 => 4,
                            1 => 4,
                            2 => [0 => 4, 1 => 4, 2 => 4],
                            3 => [0 => 4],
                            4 => [0 => [0 => 4]],
                            5 => [0 => [0 => [0 => [0 => 4]]]],
                        ],
                        'size' => [
                            0 => '',
                            1 => '',
                            2 => [0 => '', 1 => '', 2 => ''],
                            3 => [0 => ''],
                            4 => [0 => [0 => '']],
                            5 => [0 => [0 => [0 => [0 => '']]]],
                        ],
                    ],
                    'test' => [
                        'name'     => '',
                        'type'     => '',
                        'tmp_name' => '',
                        'error'    => 4,
                        'size'     => 0,
                    ],
                ],
                9,
                0,
            ]
        ];
    }
}
