 <div class="navbar topnav">
    <div class="navbar-inner">
        <div class="container">
            <div class="nav-collapse ">
                <div class="col-md-4 col-sm-4 pull-right">
                    <div class="dropdown pull-right">
            <button onclick="myFunction()" class="dropbtn dropdown-toggle">  <?php if($name){ echo $name;}else{ echo $this->session->userdata('username');}?></button>
              <div id="myDropdown" class="dropdown-content">
                 <a   href="<?php echo  base_url('login/logout');?>">Log Out</a>
              </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="pb_cover_v3 cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row">
        <div class="col-md-5 relative align-self-center verification-id page-p">
            <div class="feature-description">
                <h2>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                </h2>
                <hr>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon bg-active"> <span>01</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>02</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's </p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>03</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's </p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>04</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p></div>
                </div>
                <hr>
            </div>
        </div>

        <div class="col-md-7 col-sm-12">
                <h1 class="mb-3 heading-txt"></h1>
                <form action="<?php echo base_url('personal_information');?>" id="personal_information_validation" class="bg-white rounded pb_form_v1 page-p" style="float:left;" method="post">
                    <h2 class="mb-4 mt-0 text-center">PERSONAL INFORMATION  </h2>
                   <div style="clear:both;"><?php include('validation_message.php'); ?></div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="first_name">First Name & Middle Name</label>
                        <input type="text" name="first_name" class="form-control input-form" value ="<?php if($first_name){echo $first_name;}?>" data-bvalidator="required,alpha" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="last name">Last Name</label>
                        <input type="text" name="last_name" class="form-control input-form" value ="<?php if($last_name){echo $last_name;}?>"  data-bvalidator="required,alpha" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 pd-5 no-padding pull-left">
                        <label for="first_name" style="float: left; width: 100%; display: block;">Date of Birth </label>

                        <div class="col-md-4 pull-left pd-2">

                            <select name="day" class="form-control date-select" required="required" data-bvalidator="required">
                                <option value="">Day</option>
                                <option value="1" <?php if($day=='1'){echo ' selected="selected"';}  ?>>1</option>
                                <option value="2" <?php if($day=='2'){echo ' selected="selected"';}  ?>>2</option>
                                <option value="3" <?php if($day=='3'){echo ' selected="selected"';}  ?>>3</option>
                                <option value="4" <?php if($day=='4'){echo ' selected="selected"';}  ?>>4</option>
                                <option value="5" <?php if($day=='5'){echo ' selected="selected"';}  ?>>5</option>
                                <option value="6" <?php if($day=='6'){echo ' selected="selected"';}  ?>>6</option>
                                <option value="7" <?php if($day=='7'){echo ' selected="selected"';}  ?>>7</option>
                                <option value="8" <?php if($day=='8'){echo ' selected="selected"';}  ?>>8</option>
                                <option value="9" <?php if($day=='9'){echo ' selected="selected"';}  ?>>9</option>
                                <option value="10" <?php if($day=='10'){echo ' selected="selected"';}  ?>>10</option>
                                <option value="11" <?php if($day=='11'){echo ' selected="selected"';}  ?>>11</option>
                                <option value="12" <?php if($day=='12'){echo ' selected="selected"';}  ?>>12</option>
                                <option value="13" <?php if($day=='13'){echo ' selected="selected"';}  ?>>13</option>
                                <option value="14" <?php if($day=='14'){echo ' selected="selected"';}  ?>>14</option>
                                <option value="15" <?php if($day=='15'){echo ' selected="selected"';}  ?>>15</option>
                                <option value="16" <?php if($day=='16'){echo ' selected="selected"';}  ?>>16</option>
                                <option value="17" <?php if($day=='16'){echo ' selected="selected"';}  ?>>17</option>
                                <option value="18" <?php if($day=='18'){echo ' selected="selected"';}  ?>>18</option>
                                <option value="19" <?php if($day=='19'){echo ' selected="selected"';}  ?>>19</option>
                                <option value="20" <?php if($day=='20'){echo ' selected="selected"';}  ?>>20</option>
                                <option value="21" <?php if($day=='21'){echo ' selected="selected"';}  ?>>21</option>
                                <option value="22" <?php if($day=='22'){echo ' selected="selected"';}  ?>>22</option>
                                <option value="23" <?php if($day=='23'){echo ' selected="selected"';}  ?>>23</option>
                                <option value="24" <?php if($day=='24'){echo ' selected="selected"';}  ?>>24</option>
                                <option value="25" <?php if($day=='25'){echo ' selected="selected"';}  ?>>25</option>
                                <option value="26" <?php if($day=='26'){echo ' selected="selected"';}  ?>>26</option>
                                <option value="27" <?php if($day=='27'){echo ' selected="selected"';}  ?>>27</option>
                                <option value="28" <?php if($day=='28'){echo ' selected="selected"';}  ?>>28</option>
                                <option value="29" <?php if($day=='29'){echo ' selected="selected"';}  ?>>29</option>
                                <option value="30" <?php if($day=='30'){echo ' selected="selected"';}  ?>>30</option>
                                <option value="31" <?php if($day=='31'){echo ' selected="selected"';}  ?>>31</option>
                            </select>
                        </div>
                        <div class="col-md-4 pull-left pd-10">

                            <select name="month" class="form-control date-select" required="required" data-bvalidator="required">
                                <option value="">Month</option>
                                <option value="1" <?php if($month=='1'){echo 'selected="selected"';} ?>>January</option>
                                <option value="2" <?php if($month=='2'){echo 'selected="selected"';} ?>>February</option>
                                <option value="3" <?php if($month=='3'){echo 'selected="selected"';} ?>>March</option>
                                <option value="4" <?php if($month=='4'){echo 'selected="selected"';} ?>>April</option>
                                <option value="5" <?php if($month=='5'){echo 'selected="selected"';} ?>>May</option>
                                <option value="6" <?php if($month=='6'){echo 'selected="selected"';} ?>>June</option>
                                <option value="7" <?php if($month=='7'){echo 'selected="selected"';} ?>>July</option>
                                <option value="8" <?php if($month=='8'){echo 'selected="selected"';} ?>>August</option>
                                <option value="9" <?php if($month=='9'){echo 'selected="selected"';} ?>>September</option>
                                <option value="10" <?php if($month=='10'){echo 'selected="selected"';} ?>>October</option>
                                <option value="11" <?php if($month=='11'){echo 'selected="selected"';} ?>>November</option>
                                <option value="12" <?php if($month=='12'){echo 'selected="selected"';} ?>>December</option>
                            </select>
                        </div>
                        <div class="col-md-4 pull-left pd-2">

                            <select name="year" class="form-control date-select" required="required" data-bvalidator="required">
                                <option value="">Year</option>
                                <option value="2001" <?php if($year=='2001'){echo 'selected="selected"';} ?>>2001</option>
                                <option value="2000" <?php if($year=='2000'){echo 'selected="selected"';} ?>>2000</option>
                                <option value="1999" <?php if($year=='1999'){echo 'selected="selected"';} ?>>1999</option>
                                <option value="1998" <?php if($year=='1998'){echo 'selected="selected"';} ?>>1998</option>
                                <option value="1997" <?php if($year=='1997'){echo 'selected="selected"';} ?>>1997</option>
                                <option value="1996" <?php if($year=='1996'){echo 'selected="selected"';} ?>>1996</option>
                                <option value="1995" <?php if($year=='1995'){echo 'selected="selected"';} ?>>1995</option>
                                <option value="1994" <?php if($year=='1994'){echo 'selected="selected"';} ?>>1994</option>
                                <option value="1993" <?php if($year=='1993'){echo 'selected="selected"';} ?>>1993</option>
                                <option value="1992" <?php if($year=='1992'){echo 'selected="selected"';} ?>>1992</option>
                                <option value="1991" <?php if($year=='1991'){echo 'selected="selected"';} ?>>1991</option>
                                <option value="1990" <?php if($year=='1990'){echo 'selected="selected"';} ?>>1990</option>
                                <option value="1989" <?php if($year=='1989'){echo 'selected="selected"';} ?>>1989</option>
                                <option value="1988" <?php if($year=='1988'){echo 'selected="selected"';} ?>>1988</option>
                                <option value="1987" <?php if($year=='1987'){echo 'selected="selected"';} ?>>1987</option>
                                <option value="1986" <?php if($year=='1986'){echo 'selected="selected"';} ?>>1986</option>
                                <option value="1985" <?php if($year=='1985'){echo 'selected="selected"';} ?>>1985</option>
                                <option value="1984" <?php if($year=='1984'){echo 'selected="selected"';} ?>>1984</option>
                                <option value="1983" <?php if($year=='1983'){echo 'selected="selected"';} ?>>1983</option>
                                <option value="1982" <?php if($year=='1982'){echo 'selected="selected"';} ?>>1982</option>
                                <option value="1981" <?php if($year=='1981'){echo 'selected="selected"';} ?>>1981</option>
                                <option value="1980" <?php if($year=='1980'){echo 'selected="selected"';} ?>>1980</option>
                                <option value="1979" <?php if($year=='1979'){echo 'selected="selected"';} ?>>1979</option>
                                <option value="1978" <?php if($year=='1978'){echo 'selected="selected"';} ?>>1978</option>
                                <option value="1977" <?php if($year=='1977'){echo 'selected="selected"';} ?>>1977</option>
                                <option value="1976" <?php if($year=='1976'){echo 'selected="selected"';} ?>>1976</option>
                                <option value="1975" <?php if($year=='1975'){echo 'selected="selected"';} ?>>1975</option>
                                <option value="1974" <?php if($year=='1974'){echo 'selected="selected"';} ?>>1974</option>
                                <option value="1973" <?php if($year=='1973'){echo 'selected="selected"';} ?>>1973</option>
                                <option value="1972" <?php if($year=='1972'){echo 'selected="selected"';} ?>>1972</option>
                                <option value="1971" <?php if($year=='1971'){echo 'selected="selected"';} ?>>1971</option>
                                <option value="1970" <?php if($year=='1970'){echo 'selected="selected"';} ?>>1970</option>
                                <option value="1969" <?php if($year=='1969'){echo 'selected="selected"';} ?>>1969</option>
                                <option value="1968" <?php if($year=='1968'){echo 'selected="selected"';} ?>>1968</option>
                                <option value="1967" <?php if($year=='1967'){echo 'selected="selected"';} ?>>1967</option>
                                <option value="1966" <?php if($year=='1966'){echo 'selected="selected"';} ?>>1966</option>
                                <option value="1965" <?php if($year=='1965'){echo 'selected="selected"';} ?>>1965</option>
                                <option value="1964" <?php if($year=='1964'){echo 'selected="selected"';} ?>>1964</option>
                                <option value="1963" <?php if($year=='1963'){echo 'selected="selected"';} ?>>1963</option>
                                <option value="1962" <?php if($year=='1962'){echo 'selected="selected"';} ?>>1962</option>
                                <option value="1961" <?php if($year=='1961'){echo 'selected="selected"';} ?>>1961</option>
                                <option value="1960" <?php if($year=='1960'){echo 'selected="selected"';} ?>>1960</option>
                                <option value="1959" <?php if($year=='1959'){echo 'selected="selected"';} ?>>1959</option>
                                <option value="1958" <?php if($year=='1958'){echo 'selected="selected"';} ?>>1958</option>
                                <option value="1957" <?php if($year=='1957'){echo 'selected="selected"';} ?>>1957</option>
                                <option value="1956" <?php if($year=='1956'){echo 'selected="selected"';} ?>>1956</option>
                                <option value="1955" <?php if($year=='1955'){echo 'selected="selected"';} ?>>1955</option>
                                <option value="1954" <?php if($year=='1954'){echo 'selected="selected"';} ?>>1954</option>
                                <option value="1953" <?php if($year=='1953'){echo 'selected="selected"';} ?>>1953</option>
                                <option value="1952" <?php if($year=='1952'){echo 'selected="selected"';} ?>>1952</option>
                                <option value="1951" <?php if($year=='1951'){echo 'selected="selected"';} ?>>1951</option>
                                <option value="1950" <?php if($year=='1950'){echo 'selected="selected"';} ?>>1950</option>
                                <option value="1949" <?php if($year=='1949'){echo 'selected="selected"';} ?>>1949</option>
                                <option value="1948" <?php if($year=='1948'){echo 'selected="selected"';} ?>>1948</option>
                                <option value="1947" <?php if($year=='1947'){echo 'selected="selected"';} ?>>1947</option>
                                <option value="1946" <?php if($year=='1946'){echo 'selected="selected"';} ?>>1946</option>
                                <option value="1945" <?php if($year=='1945'){echo 'selected="selected"';} ?>>1945</option>
                                <option value="1944" <?php if($year=='1944'){echo 'selected="selected"';} ?>>1944</option>
                                <option value="1943" <?php if($year=='1943'){echo 'selected="selected"';} ?>>1943</option>
                                <option value="1942" <?php if($year=='1942'){echo 'selected="selected"';} ?>>1942</option>
                                <option value="1941" <?php if($year=='1941'){echo 'selected="selected"';} ?>>1941</option>
                                <option value="1940" <?php if($year=='1940'){echo 'selected="selected"';} ?>>1940</option>
                                <option value="1939" <?php if($year=='1939'){echo 'selected="selected"';} ?>>1939</option>
                                <option value="1938" <?php if($year=='1938'){echo 'selected="selected"';} ?>>1938</option>
                                <option value="1937" <?php if($year=='1937'){echo 'selected="selected"';} ?>>1937</option>
                                <option value="1936" <?php if($year=='1936'){echo 'selected="selected"';} ?>>1936</option>
                                <option value="1935" <?php if($year=='1935'){echo 'selected="selected"';} ?>>1935</option>
                                <option value="1934" <?php if($year=='1934'){echo 'selected="selected"';} ?>>1934</option>
                                <option value="1933" <?php if($year=='1933'){echo 'selected="selected"';} ?>>1933</option>
                                <option value="1932" <?php if($year=='1932'){echo 'selected="selected"';} ?>>1932</option>
                                <option value="1931" <?php if($year=='1931'){echo 'selected="selected"';} ?>>1931</option>
                                <option value="1930" <?php if($year=='1930'){echo 'selected="selected"';} ?>>1930</option>
                                <option value="1929" <?php if($year=='1929'){echo 'selected="selected"';} ?>>1929</option>
                                <option value="1928" <?php if($year=='1928'){echo 'selected="selected"';} ?>>1928</option>
                                <option value="1927" <?php if($year=='1927'){echo 'selected="selected"';} ?>>1927</option>
                                <option value="1926" <?php if($year=='1926'){echo 'selected="selected"';} ?>>1926</option>
                                <option value="1925" <?php if($year=='1925'){echo 'selected="selected"';} ?>>1925</option>
                                <option value="1924" <?php if($year=='1924'){echo 'selected="selected"';} ?>>1924</option>
                                <option value="1923" <?php if($year=='1923'){echo 'selected="selected"';} ?>>1923</option>
                                <option value="1922" <?php if($year=='1922'){echo 'selected="selected"';} ?>>1922</option>
                                <option value="1921" <?php if($year=='1921'){echo 'selected="selected"';} ?>>1921</option>
                                <option value="1920" <?php if($year=='1920'){echo 'selected="selected"';} ?>>1920</option>
                                <option value="1919" <?php if($year=='1919'){echo 'selected="selected"';} ?>>1919</option>
                                <option value="1918" <?php if($year=='1918'){echo 'selected="selected"';} ?>>1918</option>
                                <option value="1917" <?php if($year=='1917'){echo 'selected="selected"';} ?>>1917</option>
                                <option value="1916" <?php if($year=='1916'){echo 'selected="selected"';} ?>>1916</option>
                                <option value="1915" <?php if($year=='1915'){echo 'selected="selected"';} ?>>1915</option>
                                <option value="1914" <?php if($year=='1914'){echo 'selected="selected"';} ?>>1914</option>
                                <option value="1913" <?php if($year=='1913'){echo 'selected="selected"';} ?>>1913</option>
                                <option value="1912" <?php if($year=='1912'){echo 'selected="selected"';} ?>>1912</option>
                                <option value="1911" <?php if($year=='1911'){echo 'selected="selected"';} ?>>1911</option>
                                <option value="1910" <?php if($year=='1910'){echo 'selected="selected"';} ?>>1910</option>
                                <option value="1909" <?php if($year=='1909'){echo 'selected="selected"';} ?>>1909</option>
                                <option value="1908" <?php if($year=='1908'){echo 'selected="selected"';} ?>>1908</option>
                                <option value="1907" <?php if($year=='1907'){echo 'selected="selected"';} ?>>1907</option>
                                <option value="1906" <?php if($year=='1906'){echo 'selected="selected"';} ?>>1906</option>
                                <option value="1905" <?php if($year=='1905'){echo 'selected="selected"';} ?>>1905</option>
                                <option value="1904" <?php if($year=='1904'){echo 'selected="selected"';} ?>>1904</option>
                                <option value="1903" <?php if($year=='1903'){echo 'selected="selected"';} ?>>1903</option>
                                <option value="1902" <?php if($year=='1902'){echo 'selected="selected"';} ?>>1902</option>
                                <option value="1901" <?php if($year=='1901'){echo 'selected="selected"';} ?>>1901</option>
                                <option value="1900" <?php if($year=='1900'){echo 'selected="selected"';} ?>>1900</option>
                            </select>
                        </div>

                            <div class="clear"></div>

                        </div>
                    <div class="clear"></div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="Country">Country </label>
                        <input type="text" name="country" class="form-control input-form" value ="<?php if($country){echo $country;}?>" data-bvalidator="required" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="State">State </label>
                        <input type="text" name="state" class="form-control input-form" value ="<?php if($state){echo $state;}?>"  data-bvalidator="required" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="Country">City </label>
                        <input type="text" name="city" class="form-control input-form" value ="<?php if($city){echo $city;}?>"  data-bvalidator="required" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="Country">Zip </label>
                        <input type="text" name="zipcode" class="form-control input-form" value ="<?php if($zipcode){echo $zipcode;}?>" data-bvalidator="required" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="Country">Home address </label>
                        <input type="text" name="home_address" class="form-control input-form" value ="<?php if($home_address){echo $home_address;}?>"  data-bvalidator="required" />
                        <small class="form-control-feedback"></small>
                    </div>
                    <div class="form-group col-md-6 col-sm-12 pd-5 pull-left">
                        <label for="Country">Mailing address </label>
                        <input type="text" name="mailing_address" class="form-control input-form" value ="<?php if($mailing_address){echo $mailing_address;}?>"   />
                        <small class="form-control-feedback"></small>
                    </div>
                    <hr />
                    <div class="clear"></div>
                    <div class="form-group">
                        <div class="pull-right">
                          <button class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Continue &raquo;</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>


  </div>
</section>
