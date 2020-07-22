<?php
session_start();
include "lib/koneksi.php";
include "templates/header.php";

//jika tidak ada session pelanggan (blm melakukan login), maka dilarikan ke login.php
if (!isset($_SESSION["pelanggan"]) OR empty($_SESSION["pelanggan"])) {
    echo "<script>alert('Silahkan login');</script>";
    echo "<script>location='login.php';</script>";
}

                        $batas =5;
						$halaman =@$_GET['halaman'];
						if (empty($halaman)) {
							$posisi=0;
							$halaman=1;
						} else{
							$posisi=($halaman-1) * $batas;
						}

?>

<section  id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                        <li class="active">Riwayat Belanja</li>
                <ol>
                <h3>Riwayat Belanja <?Php echo ($_SESSION["pelanggan"]["nama"]) ?></h3>

            <div class="table-responsive cart_info">
                <table class="table table-condensed">
        <thead>
						<tr class="cart_menu">
							<td class="nomor">No</td>
							<td class="tanggal">Tanggal</td>
                            <td class="pesan">Status Pesanan</td>
							<td class="status">Status Pembayaran</td>
							<td class="total">Total</td>
							<td class="opsi">Opsi</td>
							
						</tr>
					</thead>
            <tbody>
            <?php
            $nomor=1;
            //mendapatkan id_pelanggan yang login dari session
            $id_pelanggan=$_SESSION["pelanggan"]["id_pelanggan"];

            $ambil =$koneksi->query("SELECT * FROM pesanan  WHERE id_pelanggan='$id_pelanggan' ORDER BY pesanan.id_pesanan DESC LIMIT $posisi,$batas" );
            while($pecah= $ambil->fetch_assoc()){
            ?>
                <tr>
                    <td><?php echo $nomor;?></td>
                    <td><?php echo $pecah["tgl_pesanan"];?></td>
                    <td><?php echo $pecah ["status"];?></td>
                    <td><?php echo $pecah ["status_pesanan"];?></td>
                    <td>Rp.<?php echo number_format($pecah["total_pesanan"]);?></td>
                    <td>
                        <a href="nota.php?id=<?php echo $pecah["id_pesanan"]?>" class="btn btn-info">Nota Pesanan</a>

                        <?php if ($pecah['status']=="bisa pesan"): ?>

                        <?php if ($pecah['status_pesanan']=="Pending"):?>
                        <a href="pembayaran.php?id=<?php echo $pecah["id_pesanan"];?>"class="btn btn-success" >Input Pembayaran</a>
                        <?php else: ?>
                        <a href="lihat_pembayaran.php?id=<?php echo $pecah["id_pesanan"];?>"class="btn btn-danger" >Lihat Pembayaran</a>
                        <?php endif ?>
                        <?php endif ?>
                    </td>
             </tr>
             
             <?php $nomor++; ?>
            <?php } ?>
            </tbody>
        </table>
        <?php
						/* hitung total data dan halaman serta link  */
						$ambil2 =mysqli_query($koneksi,"SELECT * FROM pesanan WHERE id_pelanggan='$id_pelanggan' ORDER BY pesanan.id_pesanan DESC");
						$jmldata=mysqli_num_rows($ambil2);

						$jmlhalaman=ceil($jmldata/$batas);
						echo "<br> Halaman :";
						for ($i=1; $i <=$jmlhalaman ; $i++) {
						if ($i != $halaman){  
							echo "<a href=\"riwayat_belanja.php?halaman=$i\">$i</a>|";
						} else {
							echo"<b>$i</b>|";
						}
						}
						echo "<p>Jumlah produk  :<b>$jmldata</b></p>";
						?>
        </div>
        </div>
    </div>
</section>


<?php
include "templates/footer.php";
?>

