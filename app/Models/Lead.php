<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name',
        'Mobile',
        'AlternateMobile',
        'Email',
        'PreferredContactTime',
        'CommunicationMode',
        'LeadType',
        'Priority',
        'Source',
        'Campaign',
        'ReferralName',
        'ProjectID',
        'PropertyType',
        'BudgetMin',
        'BudgetMax',
        'PreferredLocation',
        'Requirements',
        'LeadStage',
        'NextFollowup',
        'LastInteraction',
        'InteractionNotes',
        'AssignedTo',
        'ExpectedMoveInDate',
        'ExpectedDealValue',
        'QuoteStatus',
        'ContractSignedDate',
        'PaymentStatus'
    ];

    // Relationships

    // Lead assigned to a User
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'AssignedTo', 'ID');
    }

    // Lead linked to a Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'ProjectID', 'ID');
    }
}
