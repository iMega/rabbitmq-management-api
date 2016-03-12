<?php

namespace Rabman\Resources;

interface ExchangeInterface extends ResourceInterface, ResourceVHostInterface, ResourceCreateInterface, ResourceDeleteInterface
{
    /**
     * @return $this
     */
    public function bindings();

    /**
     * @return $this
     */
    public function source();

    /**
     * @return $this
     */
    public function destination();

    /**
     * @param array $options
     *
     * @return array
     */
    public function publish(array $options);
}
