<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\PathMethodDataTransfer;
use Generated\Shared\Transfer\PathSchemaDataTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenApiSpecificationPathGenerator implements PathGeneratorInterface
{
    protected const DESCRIPTION_DEFAULT_REQUEST = 'Expected request body.';
    protected const DESCRIPTION_DEFAULT_RESPONSE = 'Expected response to a bad request.';
    protected const DESCRIPTION_SUCCESSFUL_RESPONSE = 'Expected response to a valid request.';

    protected const KEY_DEFAULT = 'default';

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface
     */
    protected $pathMethodRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\PathMethodRendererInterface $pathMethodRenderer
     */
    public function __construct(PathMethodRendererInterface $pathMethodRenderer)
    {
        $this->pathMethodRenderer = $pathMethodRenderer;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addGetPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new PathSchemaDataTransfer();
        }

        $pathMethodDataTransfer = $this->defineDefaultSuccessfulResponseSchema(
            $pathMethodDataTransfer,
            $responseSchemaDataTransfer,
            (string)Response::HTTP_OK
        );

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_GET));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPostPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $requestSchemaDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new PathSchemaDataTransfer();
        }

        $pathMethodDataTransfer = $this->defineDefaultSuccessfulResponseSchema(
            $pathMethodDataTransfer,
            $responseSchemaDataTransfer,
            (string)Response::HTTP_CREATED
        );

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        if ($requestSchemaDataTransfer->getSchemaReference()) {
            $requestSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_REQUEST);
            $pathMethodDataTransfer->setRequestSchema($requestSchemaDataTransfer);
        }
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_POST));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $requestSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer|null $responseSchemaDataTransfer
     *
     * @return void
     */
    public function addPatchPath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $requestSchemaDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer,
        ?PathSchemaDataTransfer $responseSchemaDataTransfer
    ): void {
        if (!$responseSchemaDataTransfer) {
            $responseSchemaDataTransfer = new PathSchemaDataTransfer();
        }

        $pathMethodDataTransfer = $this->defineDefaultSuccessfulResponseSchema(
            $pathMethodDataTransfer,
            $responseSchemaDataTransfer,
            (string)Response::HTTP_OK
        );

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        if ($requestSchemaDataTransfer->getSchemaReference()) {
            $requestSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_REQUEST);
            $pathMethodDataTransfer->setRequestSchema($requestSchemaDataTransfer);
        }
        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_PATCH));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $errorSchemaDataTransfer
     *
     * @return void
     */
    public function addDeletePath(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $errorSchemaDataTransfer
    ): void {
        $responseSchemaDataTransfer = new PathSchemaDataTransfer();
        $pathMethodDataTransfer = $this->defineDefaultSuccessfulResponseSchema(
            $pathMethodDataTransfer,
            $responseSchemaDataTransfer,
            (string)Response::HTTP_NO_CONTENT
        );

        $errorSchemaDataTransfer->setCode(static::KEY_DEFAULT);
        $errorSchemaDataTransfer->setDescription(static::DESCRIPTION_DEFAULT_RESPONSE);

        $pathMethodDataTransfer->addResponseSchema($errorSchemaDataTransfer);
        $pathMethodDataTransfer->setMethod(strtolower(Request::METHOD_DELETE));

        $this->addPath($pathMethodDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addPath(PathMethodDataTransfer $pathMethodDataTransfer): void
    {
        $this->paths = array_replace_recursive($this->paths, $this->pathMethodRenderer->render($pathMethodDataTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param string $defaultMethodStatusCode
     *
     * @return string
     */
    protected function getResponseStatusCode(PathMethodDataTransfer $pathMethodDataTransfer, string $defaultMethodStatusCode): string
    {
        return $pathMethodDataTransfer->getIsEmptyResponse() ? (string)Response::HTTP_NO_CONTENT : $defaultMethodStatusCode;
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return bool
     */
    protected function isSuccessResponseCodeDeclared(PathMethodDataTransfer $pathMethodDataTransfer): bool
    {
        foreach ($pathMethodDataTransfer->getResponseSchemas() as $responseSchema) {
            $responseSchemaCode = (int)$responseSchema->getCode();
            if ($responseSchemaCode >= 200 && $responseSchemaCode < 300) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $responseSchemaDataTransfer
     * @param string $defaultSuccessfulCode
     *
     * @return \Generated\Shared\Transfer\PathMethodDataTransfer
     */
    protected function defineDefaultSuccessfulResponseSchema(
        PathMethodDataTransfer $pathMethodDataTransfer,
        PathSchemaDataTransfer $responseSchemaDataTransfer,
        string $defaultSuccessfulCode
    ): PathMethodDataTransfer {
        if (!$this->isSuccessResponseCodeDeclared($pathMethodDataTransfer)) {
            $responseSchemaDataTransfer->setCode(
                $this->getResponseStatusCode($pathMethodDataTransfer, $defaultSuccessfulCode)
            );
            $responseSchemaDataTransfer->setDescription(static::DESCRIPTION_SUCCESSFUL_RESPONSE);
            $pathMethodDataTransfer->addResponseSchema($responseSchemaDataTransfer);
        }

        return $pathMethodDataTransfer;
    }
}
