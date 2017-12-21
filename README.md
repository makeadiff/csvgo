# CSVGo

This is a repository for a lot of SQL queries that will output the data in a CSV format. Used to get data from MADApp/Donut databases to google Spreadsheets or other apps. 

## Components...

### Control Tool

This is the admin area where you can see all the Sql queries listed out. Each will have a name, description, query, vertical and status. Also includes a area to create/edit SQL queries.

### CSV Output

The index.php?name=[csvgo-query-name] will output the data in the CSV format. It suppors the following parameters

1. name - CSVGo Query Name. 
2. no_cache - Gets live data if this is true
3. mime - If you want to see the data on the screen, pass mime=html or mime=plain. Else it will force a download(octect-stream).

### Code Execution

If the there is a file called 'code/[csvgo-query-name].php' it will execute that and return the data as CSV. This is used for data that can't be done using a Query.

## Things to Know...

* Cache:
	By default all queries are cached for 1 day. Cacheing is done using Memcache
* no_cache:
	If you want the live data, just pass the no_cache=1 parameter with the URL. For eg. http://makeadiff.in/apps/csvgo/?name=volunteer_list&no_cache=1 . This will also cause the cached data to get updated as well.
* Parameter Replacements:
	A few things in the query can be replaced based on the parameters passed at execution. For eg. %BATCH_ID% will be replaced with [x] when ?batch_id=[x] is passed.

# Purpose

This is a glue tool that lets data flow between multiple unconnected projects. For eg...
 * You want to get data from MADApp regarding applicant signup when creating a sourcing dashboard in Google Spreadsheet. 
 * Or you want to import all teachers into a Mailing list. 

This is a helper tool for tech team. It was made when there was too many CSV requests from the organization. Hence, no formal project documentation other than this file.

# Dependencies

* IFrame
* Memcached
* Apps/Common
* Apps/reports/
* Apps/support/


## Todo

* Parameter based replacements
* Better explanation of Query replacements(Eg. %YEAR% will relpace to current year).
