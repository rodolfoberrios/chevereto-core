<?php declare(strict_types=1);
/*
 * This file is part of Chevereto\Core.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Chevereto\Core\Traits;

trait DataTrait
{
    protected $data;
    public function addData(array $data) : self
    {
        if (null == $this->data) {
            $this->data = $data;
        } else {
            $this->data = array_replace_recursive($this->data, $data);
        }
        return $this;
    }
    public function setData(array $data) : self
    {
        $this->data = $data;
        return $this;
    }
    public function getData() : ?array
    {
        return $this->data;
    }
    public function hasDataKey(string $key) : bool
    {
        return array_key_exists($key, $this->data);
    }
    public function setDataKey(string $key, $var) : self
    {
        $this->data[$key] = $var;
        return $this;
    }
    public function appendData($var) : self
    {
        $this->data[] = $var;
        return $this;
    }
    public function getDataKey(string $key)
    {
        return $this->data[$key] ?? null;
    }
    public function removeDataKey(string $key) : self
    {
        unset($this->data[$key]);
        return $this;
    }
}