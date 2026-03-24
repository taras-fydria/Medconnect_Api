<?php

namespace App\Doctor;

enum Specialization: string
{
    case GeneralPractice = 'General Practice';
    case GeneralSurgery = 'General Surgery';
    case Cardiology = 'Cardiology';
    case Neurology = 'Neurology';
}
