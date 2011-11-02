#Purpose
This is very simple framework to help with building HTML mockups / prototypes

#Usage

##Prerequisites
* The framework must run through apache as it uses a .htaccess file to rewrite URLs
* The framework requires PHP 5.3

##Getting Started
1. Edit /layout/layout.php to include your site skeleton (Doctype, HTML start and end etc.)
2. Print or echo $this->layout->view at the point in your layout which can change depending on the page you are viewing
3. Create a view file (see views/test.php for an example)
4. Edit the view file to output anything you like (this will be placed into the layout at the point where $this->layout->view is echoed)
5. Open a browser and type the URL of the location you set up the framework followed by your view file name minus the .php extension (eg. http://localhost/prototype/test)
6. Create as many views as you like
7. You can pass variables from a view to it's containing layout via the $this->layout registry (eg. $this->layout->title = 'My Title'). These variables can be accessed in the same way in the layout (eg. echo $this->layout->title)
8. Create different layouts if you need to and in the views which you want to use those layouts begin with <?php $this->setLayoutName('layoutFileName'); ?> (notice the missing .php extension)
9. Create view helpers in /views/helpers (see Button.php as an example). These are simply classes which extend ViewHelper in /lib/ViewHelper.php and implement function invoke(). invoke() can accept any number of arguments and can be called from within a layout or view by simply calling <?php echo $this->viewHelperName([$arg1, [$arg2, ...]]); ?> - eg. <?php echo $this->button('Button text'); ?>

##Exporting
To export simply open a command prompt and execute `php export.php`. This will create a directory called 'export_<datetime>' which will include a compiled set of static .html files along with any other assets such as images, css, sass, javascript files etc. ready to be zipped and emailed as a stand alone implementation.