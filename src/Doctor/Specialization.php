<?php

namespace App\Doctor;

enum Specialization: string
{
    case GeneralSurgery = 'general_surgery';
    case Cardiology     = 'cardiology';
    case Neurology      = 'neurology';
}