<?php

class Article
{

    public $id;
    public $title;
    public $content;
    public $publisched_at;
    public $image_file;
    public $error = [];

    public static function getAll($conn)
    {

        $sql = "SELECT * FROM article ORDER BY title;";

        $result = $conn->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPage($conn, $limit, $offset, $only_publisched = false)
    {


        $condition = $only_publisched ? ' WHERE published_at IS NOT NULL' : '';

        $sql = "SELECT a.*, category.name AS category_name FROM (SELECT * FROM article $condition ORDER BY title LIMIT :limit OFFSET :offset) AS a LEFT JOIN article_category ON a.id = article_category.article_id LEFT JOIN category ON article_category.category_id = category.id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $articles = [];

        $previous_id = null;

        foreach ($result as $row) {

            $article_id = $row['id'];

            if ($article_id != $previous_id) {
                $row['category_names'] = [];

                $articles[$article_id] = $row;
            }

            $articles[$article_id]['category_names'][] = $row['category_name'];

            $previous_id = $article_id;
        }

        return $articles;
    }


    public static function getByID($conn, $id, $columns = '*')
    {

        $sql = "SELECT $columns FROM article WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');

        if ($stmt->execute()) {

            return $stmt->fetch();
        }
    }

    public static function getWithCategories($conn, $id, $only_publisched = false)
    {

        $sql = "SELECT article.* , GROUP_CONCAT(category.name SEPARATOR ', ') AS category_name FROM article LEFT JOIN article_category ON article.id = article_category.article_id LEFT JOIN category ON article_category.category_id = category.id WHERE article.id = :id";

        if ($only_publisched) {
            $sql .= ' AND article.published_at IS NOT NULL';
        }

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');

        $stmt->execute();

        return $stmt->fetch();
    }

    public function getCategories($conn)
    {

        $sql = "SELECT category.* FROM category JOIN article_category ON category.id = article_category.category_id WHERE article_id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($conn)
    {

        if ($this->validate()) {

            $sql = "UPDATE article SET title= :title, content= :content, published_at= :published_at WHERE id= :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT); // $this->calling beacuse it is not static so it points to a variable of the current class
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            if ($this->publisched_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);

            } else {
                $stmt->bindValue(':published_at', $this->publisched_at, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } else {
            return false;
        }
    }

    public function setCategories($conn, $ids)
    {

        if ($ids) {
            $sql = "INSERT IGNORE INTO article_category (article_id, category_id) VALUES ";

            $values = [];

            foreach ($ids as $id) {

                $values[] = "({$this->id}, ?)";
            }

            $sql .= implode(",", $values);
            $stmt = $conn->prepare($sql);

            foreach ($ids as $i => $id) {
                $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
        }

        $sql = "DELETE FROM article_category WHERE article_id = {$this->id}";

        if ($ids) {
            $placeholders = array_fill(0, count($ids), '?');

            $sql .= " AND category_id NOT IN (" . implode(",", $placeholders) . ")";
        }

        $stmt = $conn->prepare($sql);

        foreach ($ids as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
    }


    protected function validate()
    {

        if ($this->title == '') {
            $this->error[] = 'Title is required';
        }

        if ($this->content == '') {
            $this->error[] = 'Content is required';
        }

        if ($this->publisched_at != '') {
            $date_time = date_create_from_format('Y-m-d H:i:s', $this->publisched_at);
            if ($date_time == false) {
                $this->error[] = 'Invalid date and time';
            } else {
                $date_error = date_get_last_errors();
                if ($date_error['warning_count'] > 0) {
                    $this->error[] = 'Invalid date and time';
                }
            }
        }
        return empty($this->error);
    }

    public function delete($conn)
    {

        $sql = "DELETE FROM article WHERE id= :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);


        return $stmt->execute();

    }

    public function create($conn)
    {

        if ($this->validate()) {

            $sql = "INSERT INTO article (title, content, published_at) VALUES (:title, :content, :published_at)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            if ($this->publisched_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);

            } else {
                $stmt->bindValue(':published_at', $this->publisched_at, PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                $this->id = $conn->lastInsertID();
                return true;
            }


        } else {
            return false;
        }
    }

    public static function getTotal($conn, $only_publisched = false)
    {

        $condition = $only_publisched ? ' WHERE published_at IS NOT NULL' : '';

        return $conn->query("SELECT  COUNT(*) FROM article$condition")->fetchColumn();

    }

    public function setImageFile($conn, $filename)
    {

        $sql = "UPDATE article SET image_file= :image_file WHERE id= :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':image_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function publish($conn)
    {

            $sql = "UPDATE article SET published_at = :published_at WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            $publisched_at = date("Y-m-d H:i:s");
            $stmt->bindValue(':published_at', $publisched_at, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $publisched_at;
            }
    }


}
