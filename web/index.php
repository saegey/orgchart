<?php
    // web/index.php
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../lib/orgChart.php';
    require_once __DIR__.'/../lib/pagination.php';
    require_once __DIR__.'/../lib/employee.php';

    use Symfony\Component\HttpFoundation\Request;

    $app = new Silex\Application();
    $app['debug'] = getenv('DEBUG') || false; 

    $app->register(new Silex\Provider\DoctrineServiceProvider(), array(
        'db.options' => array(
            'driver'   => 'pdo_mysql',
            'dbname'   => getenv('DB_NAME'),
            'host'     => getenv('DB_HOST'),
            'user'     => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'port'     => getenv('DB_PORT') || 3306
        ),
    ));

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app->get('/', function (Request $request) use ($app) {
        $orgChart = new OrgChart(Employee::all($app['db']));
        $employeeGrid = $orgChart->calc();

        // search
        if (strlen($request->query->get('q')) > 0) {
            $orgChart->filterByName($request->query->get('q'));
        }

        // sort field and order
        $sort = $request->query->get('sort');
        $sortOrder = $request->query->get('sortOrder');
        if (strlen($sort) > 0) {
            $validFields = array("name", "bossName", "level", "subordinates");
            $validOrders = array("asc", "desc");
            if (in_array($sort, $validFields) && in_array($sortOrder, $validOrders)) {
                $orgChart->sortBy($sort, $sortOrder);
            }
        }

        // pagination
        if (strlen($request->query->get('page')) > 0) {
            $page = $request->query->get('page');
        } else {
            $page = 1;
        }
        $pagination = new Pagination($orgChart->getCalcData());
        $employeeGrid = $pagination->getResultsPage($page);

        return $app['twig']->render('orgChart.html.twig', array(
            'employees'   => $employeeGrid,
            'page'        => $page,
            'numPages'    => $pagination->numPages(),
            'searchQuery' => $request->query->get('q'),
            'sort'        => $sort,
            'sortOrder'   => $sortOrder
        ));
    });

    $app->run();
?>