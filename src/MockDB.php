<?php
namespace CrazyFactory\Translations;

class MockDB
{
    public function update($id = null)
    {
        return $id ?: false;
    }

    public function insert($id)
    {
        return $id ?: false;
    }
}
