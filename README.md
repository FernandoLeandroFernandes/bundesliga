# Bundesliga Matches Viewer

This is a simple interview application developed in Laravel/PHP and SQLite for showing the german Fußball-Bundesliga games data using the JSON/XML API provided by the [OpenLigaDB project][1].


## Instalation

To configure the Homestead Vagrant box cofigured for this project, run the command below in a terminal:

```
$ bash <(curl -s https://raw.githubusercontent.com/FernandoLeandroFernandes/bundesliga/master/install.sh)
```

It will download the installation script that will take care of the application setup. It was prepared to:
  * create the necessary file structure;
  * download the application and <code>laravel/homestead</code> repositories;
  * setup the <code>Vagrantfile</code>
  * if necessary, install <code>vagrant</code> and <code>virtualbox</code>
  * launch the Vagrant instance

After that, you should be able to acess the application through http://192.168.33.10:8000/

## Development workflow

Our workflow to develop this application started with the analysis of the data provided (OpenLigaDB project). It provides a REST service of the german soccer championship.

The service returns XML formatted data by default. However, the performance isn't the strongest point. Concerned with the performance of the site, a caching strategy for the accessed data was implemented storing the informations needed to provide the Bundesliga application services (Season Matches, Next Matches of the Season and the Winning Ratio by Team). Basically, three informations are stored: leagues, teams and matches.

Instead of a mirroring, our choice was compile in a SQLite database only the necessary information for our purpose, aiming efficiency and conciseness. A very versatile PHP library ([Httpful][2]) was used for parsing the XML data received.

When serving a request the backend verifies if the requested league wasn't already locally compiled. If not or if there is a match not yet updated, it synchronizes the league season information.

As a design choice, we put the database to work in order to extract the desired information, like in the Winning Ratio of the league teams feature, rather than seeking for it iteratively in the code. This reduces the amount of data transmitted from the database server and the application server, when using a distributed database.

For the development we choose PHP wich is efficient and lightweight besides our familiarity with it. Our framework of choice was Lavarel 5 since it is a mature framework for PHP development and, as such, offers a wide range of extensions and features like Pagination, Database migrations, Eloquent ORM and Collections (as well as raw database querying), and specially the Vagrant Homestead virtual box.

[1]: http://www.openligadb.de/
[2]: http://phphttpclient.com/