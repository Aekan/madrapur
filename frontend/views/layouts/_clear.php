<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

\frontend\assets\FrontendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html amp lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>" />
        <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo Html::encode($this->title) ?></title>
        <?php /**
 * Uj metak innetol kezdodoen
 *
 *
 * */?>
        <!-- SITE WIDE META BLOCK START -->
        <meta name="referrer" content="unsafe-url">
        <meta name="apple-mobile-web-app-title" content="Budapest River Cruise">
        <meta name="application-name" content="Budapest River Cruise">
        <meta name="theme-color" content="#ffffff">

        <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/assets/images/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/assets/images/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/assets/images/favicons/manifest.json">
        <link rel="shortcut icon" href="/assets/images/favicons/favicon.ico">
        <link rel="mask-icon" href="/assets/images/favicons/safari-pinned-tab.svg" color="#000000">
        <!-- SITE WIDE META BLOCK END -->

        <!-- SPECIFIC META BLOCK START -->
        <meta name="keywords" content="TEST" />
        <meta name="description" content="TEST" />
        <meta name="author" content="TEST" />
        <meta name="news_keywords" content="TEST" />
        <meta name="m:publication" content="TEST" />
        <meta name="m:publication_local" content="TEST" />

        <meta property="fb:pages" content="TEST" />
        <meta property="og:type" content="TEST" />
        <meta property="og:url" content="TEST/" />
        <meta property="og:title" content="TEST" />
        <meta property="og:updated_time" content="TEST" />
        <meta property="og:description" content="TEST" />
        <meta property="og:image:alt" content="TEST" />
        <meta property="og:image" content="TEST" />
        <meta property="article:publisher" content="TEST" />

        <link rel="alternate" type="application/rss+xml" title="TEST" href="/TEST/rss/" />
        <link rel="canonical" href="TEST" />
        <!-- SPECIFIC META BLOCK END -->

        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "name": "budapestrivercruise.co.uk",
                "url": "https://budapestrivercruise.co.uk/",
                "sameAs": [
                    "https://www.facebook.com/budapestrivercruise.co.uk/",
                    "https://www.pinterest.com/budapestrivercruise.co.uk/",
                    "https://twitter.com/budapestrivercruise.co.uk",
                    "https://www.instagram.com/budapestrivercruise.co.uk/"
                ],
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "https://budapestrivercruise.co.uk/product?s={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        </script>






        <?php $this->head() ?>
        <?php echo Html::csrfMetaTags() ?>

        <link rel="stylesheet" href="https://unpkg.com/react-day-picker/lib/style.css">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <meta name="google-site-verification" content="yD8mZDrcCpe0F8CoQMoB3kn0sls0ebmA0VQA1wbIpHw" />
    </head>

    <body>
        <?php $this->beginBody() ?>
            <?php echo $content ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
