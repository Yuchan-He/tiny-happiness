
## ting-happiness概要
ユーザーが投稿に通して、シェアしたい記事をアップロードする

## 実装環境
インフラ：AWS
サーバー側：PHP、Laravel 5.8
ソート管理：Git
フロント側：Html,CSS,JavaScript,jQuery

## 機能一覧
■記事一覧表示
■記事詳細表示
■記事投稿/コメントをつける
■ユーザー新規登録
■ユーザーログイン/ログアウト
■DB操作：Create（生成）、Read（読み取り）、Update（更新）、Delete（削除）
■DBテーブルのリレーション操作
■権限分け（RBAC｜ロールベースアクセス制御）
■Ajax機能(jQuery)
■画像アップロード

## 機能の詳細
■記事一覧表示
　・Laravelのページネーションを使い、configの中にページ数を定義することで、一括で修正することは可能　
■記事詳細表示
　・登録済みのユーザーが記事の中でコメントできる
　・記事と記事のコメントをすべて表示する
■記事投稿/コメントをつける
　・ユーザーのログイン状態を確認してから、登録済みのユーザーがコメントできる
　・画像アップロードできる(下記の【画像アップロード】にご参照)
■ユーザー新規登録
　・新規登録するとき、提出前に、ユーザー名の使用状況を確認(下記の【Ajax機能(jQuery)】にご参照)
■ユーザーログイン/ログアウト
　・ログインするとき、ユーザー名とパスワードのバリデーションを検証する
　・ログアウトしない限り、ログイン情報がSessionに保存し、2時間以内再度ログインする必要がない
■DB操作：Create（生成）、Read（読み取り）、Update（更新）、Delete（削除）
　・Create（生成）とき、正規表現とバリデーションでデータの有効性を確認
　・Read（読み取り）、キーワードによりの検索
　・Update（更新）、登録済みのデータが表示されたうえで更新する
　・Delete（削除）、論理削除と物理削除を分ける
■DBテーブルのリレーション操作
　・ユーザーのIDから役割を取得(1対多の逆関係　→　belongsTo)
　・役割から権限を取得(多対多　→　belongsToMany)
　・記事からすべてのコメントを取得(1対多　→　hasMany)
　・記事のIDから記事のユーザーを取得(1対多の逆関係　→　belongsTo)
　・コメントのIDからコメントのユーザーを取得(1対多の逆関係　→　belongsTo)
■権限分けで管理操作（RBAC｜ロールベースアクセス制御）
　・Middlewareを設定して、ログインしていないユーザーは管理画面へアクセスできない
　・管理者:すべての権限を持つ
　・スタッフ：管理者から付与した権限範囲内で操作
　・一般ユーザー：自分のデータしか操作できない
　・権限以外の画面が表示しない
■Ajax機能(jQuery)
　・新規登録するとき、ユーザー名が存在するかどうか
　・crud操作する時、Ajaxで送信
■画像アップロード
　・API(Webupload)を使って、画像アップロードする
　・画像がアップロードしないとき、ディフォルトの画像を使う
■単体テスト


