<?php
// automatically generated by the FlatBuffers compiler, do not modify

namespace App\Library;

use App\Library\Google\Struct;
use App\Library\Google\Table;
use App\Library\Google\ByteBuffer;
use App\Library\Google\FlatBufferBuilder;

class ContactList extends Table
{
    /**
     * @param ByteBuffer $bb
     * @return ContactList
     */
    public static function getRootAsContactList(ByteBuffer $bb)
    {
        $obj = new ContactList();
        return ($obj->init($bb->getInt($bb->getPosition()) + $bb->getPosition(), $bb));
    }

    /**
     * @param int $_i offset
     * @param ByteBuffer $_bb
     * @return ContactList
     **/
    public function init($_i, ByteBuffer $_bb)
    {
        $this->bb_pos = $_i;
        $this->bb = $_bb;
        return $this;
    }

    /**
     * @returnVectorOffset
     */
    public function getList($j)
    {
        $o = $this->__offset(4);
        $obj = new Contact();
        return $o != 0 ? $obj->init($this->__indirect($this->__vector($o) + $j * 4), $this->bb) : null;
    }

    /**
     * @return int
     */
    public function getListLength()
    {
        $o = $this->__offset(4);
        return $o != 0 ? $this->__vector_len($o) : 0;
    }

    /**
     * @param FlatBufferBuilder $builder
     * @return void
     */
    public static function startContactList(FlatBufferBuilder $builder)
    {
        $builder->StartObject(1);
    }

    /**
     * @param FlatBufferBuilder $builder
     * @return ContactList
     */
    public static function createContactList(FlatBufferBuilder $builder, $list)
    {
        $builder->startObject(1);
        self::addList($builder, $list);
        $o = $builder->endObject();
        return $o;
    }

    /**
     * @param FlatBufferBuilder $builder
     * @param VectorOffset
     * @return void
     */
    public static function addList(FlatBufferBuilder $builder, $list)
    {
        $builder->addOffsetX(0, $list, 0);
    }

    /**
     * @param FlatBufferBuilder $builder
     * @param array offset array
     * @return int vector offset
     */
    public static function createListVector(FlatBufferBuilder $builder, array $data)
    {
        $builder->startVector(4, count($data), 4);
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $builder->putOffset($data[$i]);
        }
        return $builder->endVector();
    }

    /**
     * @param FlatBufferBuilder $builder
     * @param int $numElems
     * @return void
     */
    public static function startListVector(FlatBufferBuilder $builder, $numElems)
    {
        $builder->startVector(4, $numElems, 4);
    }

    /**
     * @param FlatBufferBuilder $builder
     * @return int table offset
     */
    public static function endContactList(FlatBufferBuilder $builder)
    {
        $o = $builder->endObject();
        return $o;
    }

    public static function finishContactListBuffer(FlatBufferBuilder $builder, $offset)
    {
        $builder->finish($offset);
    }
}