<?php

namespace App\Enums;

enum Subjects: string
{
    // Core Sciences
    case Accounting = 'Accounting';
    case Biology = 'Biology';
    case Chemistry = 'Chemistry';
    case Physics = 'Physics';
    
    // Mathematics
    case Maths = 'Mathematics';
    case FurtherMaths = 'Further Maths';
    
    // Business & Social Sciences
    case Commerce = 'Commerce';
    case Economics = 'Economics';
    case Marketing = 'Marketing';
    
    // Technology
    case Computer = 'Computer';
    case ComputerScience = 'Computer Science';
    case DataProcessing = 'Data Processing';
    
    // Languages
    case English = 'English';
    case Literature = 'English Literature';
    
    // Arts & Humanities
    case History = 'History';
    case Geography = 'Geography';
    case Government = 'Government';
    
    // Agricultural Sciences
    case Agric = 'Agricultural Science';
    case AnimalHusbandry = 'Animal Husbandry';
    
    // Religious Studies
    case CRS = 'Christian Religious Studies';
    case IRK = 'Islamic Religious Studies';
    case IslamicStudies = 'Islamic Studies';
    
    // Civic Education
    case Civiledu = 'Civic Education';
        
    /**
     * Get all subject values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    /**
     * Get subject by name (case-insensitive)
     */
    public static function fromName(string $name): ?self
    {
        $name = strtolower(trim($name));
        foreach (self::cases() as $subject) {
            if (strtolower($subject->name) === $name) {
                return $subject;
            }
        }
        return null;
    }
}