<?php

namespace App\Support;

/**
 * UI boundary only: the caller must authorize and scope these records before mounting.
 * No model queries, authentication, persistence, or EVM publication happen here.
 *
 * projects: list<{id:string,code:string,name:string,location:string,progress:float}>
 * reports: list<{id:string,project_id:string,project_name:string,week:int,
 *   period:string,status:string,revision:int,reason:string}>
 * context: {id:string,name:string,week:int,start:string,end:string,status:string,
 *   baseline:string,items:list<{id:string,category:string,name:string,volume:float,
 *   unit:string,weight:float,target:float,previous:float}>}
 * Item percentages use 0..100. weight = project weight; target = cumulative item
 * plan; previous = previously approved cumulative item actual. Input = weekly
 * increment. All arithmetic is a UI estimate, never authoritative EVM data.
 */
final class PelaksanaUiData
{
    public const STATUSES = [
        'DRAFT' => 'Draft',
        'PENDING_APPROVAL' => 'Menunggu Persetujuan',
        'REJECTED' => 'Ditolak',
        'APPROVED' => 'Disetujui',
    ];
}
