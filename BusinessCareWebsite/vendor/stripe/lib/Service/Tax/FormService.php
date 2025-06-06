<?php

// File generated from our OpenAPI spec

namespace Stripe\Service\Tax;

/**
 * @phpstan-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 *
 * @psalm-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
class FormService extends \Stripe\Service\AbstractService
{
    /**
     * Returns a list of tax forms which were previously created. The tax forms are
     * returned in sorted order, with the oldest tax forms appearing first.
     *
     * @param null|array{ending_before?: string, expand?: string[], limit?: int, payee: array{account?: string, external_reference?: string, type?: string}, starting_after?: string, type?: string} $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @return \Stripe\Collection<\Stripe\Tax\Form>
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/tax/forms', $params, $opts);
    }

    /**
     * Download the PDF for a tax form.
     *
     * @param string $id
     * @param callable $readBodyChunkCallable
     * @param null|array{expand?: string[]} $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @return mixed
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function pdf($id, $readBodyChunkCallable, $params = null, $opts = null)
    {
        $opts = \Stripe\Util\RequestOptions::parse($opts);
        if (!isset($opts->apiBase)) {
            $opts->apiBase = $this->getClient()->getFilesBase();
        }

        return $this->requestStream('get', $this->buildPath('/v1/tax/forms/%s/pdf', $id), $readBodyChunkCallable, $params, $opts);
    }

    /**
     * Retrieves the details of a tax form that has previously been created. Supply the
     * unique tax form ID that was returned from your previous request, and Stripe will
     * return the corresponding tax form information.
     *
     * @param string $id
     * @param null|array{expand?: string[]} $params
     * @param null|RequestOptionsArray|\Stripe\Util\RequestOptions $opts
     *
     * @return \Stripe\Tax\Form
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/tax/forms/%s', $id), $params, $opts);
    }
}
