<?php
include 'missingfiles.php';

print_missing_from_directory('/home/janko/Daten/Bilder/Bonn', 'bildquellen.sqlite', 'bildquellen', 'dateiname'); // expected: complete directory
print_missing_from_directory('hallo', 'bildquellen.sqlite', 'bildquellen', 'dateiname'); // expected: error
print_missing_from_directory('/home/janko/Daten/Bilder/Bonn', 'hallo', 'bildquellen', 'dateiname'); // expected: error
print_missing_from_directory('/home/janko/Daten/Bilder/Bonn', 'bildquellen.sqlite', 'hallo', 'dateiname'); // expected: error
print_missing_from_directory('/home/janko/Daten/Bilder/Bonn', 'bildquellen.sqlite', 'bildquellen', 'hallo'); // expected: error
print_missing_from_directory('/home/janko/Daten/Bilder/Essen', 'bildquellen.sqlite', 'bildquellen', 'dateiname'); // expected: empty list
