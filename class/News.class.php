<?php

class News {

    private $db;
    private $level = 0;

    public function __construct() {
        include('DB.class.php');
        $dbc = new DB();
        $this->db = $dbc->getDB();
    }

    public function getHeader() {
        return '<header>
                    <span>Good News</span>
                </header>';
    }

    public function getUserInfoPanel() {
        return '<div class="userinfo">Hallo ' . ucfirst($_SESSION['username']) . ', willkommen zurück. <a href="index.php?logout=true"><button>Logout</button></a></div>';
    }

    public function getPosts($parent_id = NULL) {
        $output = '';
        if ($parent_id === NULL) {
            $stmt = "SELECT * FROM posts WHERE parent_ID IS NULL ORDER BY created";
        } else {
            $stmt = "SELECT * FROM posts WHERE parent_ID = '$parent_id' ORDER BY created";
        }
        $result = $this->db->query($stmt);
        while ($row = $result->fetch_assoc()) {
            $output .= '<article style="padding-left: '.($this->level*2).'em">
                            <header>
                                '.$row['title'].'
                            </header>
                            <section>
                                '.$row['content'].'
                            </section>
                            <footer>
                                <span>
                                    verfasst von <a href="#">'.$row['user'].'</a> am '.$row['created'].'
                                </span>
                                <span>
                                    <button>Antworten</button>
                                </span>
                            </footer>
                        </article>';
            $this->level++;
            $output .= $this->getPosts($row['pid']);
            $this->level--;
        }
        return $output;
    }

}