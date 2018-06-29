<?php

namespace Districts\Router;


class LinksCollection
{
    private $links = [];

    public function add(string $name, Link $link)
    {
        $this->links[$name] = $link;
    }

    /**
     * @param string|null $name
     * @return mixed
     * @throws \Exception
     */
    public function get(string $name = null)
    {
        if (array_key_exists($name, $this->links)) {
            return $this->links[$name];
        }
        throw new \Exception("There is no '$name' path in links collection" . PHP_EOL);
    }
}
