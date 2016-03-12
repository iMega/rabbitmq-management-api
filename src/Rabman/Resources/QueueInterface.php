<?php

namespace Rabman\Resources;

interface QueueInterface extends ResourceInterface, ResourceVHostInterface, ResourceCreateInterface, ResourceDeleteInterface
{
    /**
     * @return $this
     */
    public function bindings();

    public function contents();

    /**
     * @param array $options
     *
     * @return array
     */
    public function actions(array $options);

    /**
     * @param array $options
     *
     * @return array
     */
    public function get(array $options);
}
