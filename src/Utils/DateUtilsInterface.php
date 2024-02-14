<?php

namespace App\Utils;

use DateTimeImmutable;

interface DateUtilsInterface
{
    public function getCreateAt(): ?DateTimeImmutable;

    public function setCreateAt(DateTimeImmutable $createAt): self;

    public function getUpdateAt(): ?DateTimeImmutable;

    public function setUpdateAt(DateTimeImmutable $createAt): self;

    public function OnInitialSave();
    public function OnUpdate();


}