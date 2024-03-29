# SP-ICP-MS_particle_integration_counting
particle integration and counting tool for single-particle ICP-MS analysis

Instructions:

1. Download the file: php-7.0.33-Win32-VC14-x86-dev.zip
2. Unpack the content to C:\php
3. Save the files "peak_integration.php" and "start_integration.rename_to_bat" into your working directory and create a folder named "csv_files" within the same working directory
4. Paste the exported csv-files from single-particle ICP-MS measurements into the folder "csv_files"
5. Rename the file "start_integration.rename_to_bat" to "start_integration.bat"
6. Open the file "peak_integration.php" and set the minimum number of dwell-times in a row which are considered a particle peak "min_nr_of_dwell_for_peak = 3" as well as the "background_cutoff = 3" (which is the minimal signal intensity in counts considered to be above background) to the respective numbers determined for your nanomaterial
7. Close the file again and double-click on "start_integration.bat" to execute the script
8. The peak integration results for each measurment-data csv.file will be automatically saved in a new folder named "results" and the results csv-files are structured as follows: number of "dwell-times" integrated for each peak, sum of counts constituting the peak (giving the peak intensity).
9. The number of particle peaks in each measurement csv.file will be displayed in the pop-up window "Nr. of peaks", or can be retrieved from each results csv-file counting the number of data lines.
10. The files "example_data-file" and "example_result-file" give you an idea on how the measurement-data input csv-files have to be structured and how the result csv-fils are structured 

