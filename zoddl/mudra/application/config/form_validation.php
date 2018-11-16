<?php
$config = array(		
                                    
                'login_user' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'E-mail',
                                            'rules' => 'required|trim|xss_clean|htmlspecialchars|max_length[50]|valid_email'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required|trim|xss_clean|htmlspecialchars|min_length[2]|max_length[20]'
                                         )
                                    ),
                'lostpassword' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'E-mail',
                                            'rules' => 'required|valid_email'
                                         )
                                    ),
                'single_entry_form' => array(
                                    array(
                                            'field' => 'etypesingle',
                                            'label' => 'E.Type',
                                            'rules' => 'required'
                                         ),

                                     array(
                                            'field' => 'datesingle',
                                            'label' => 'Date',
                                            'rules' => 'required'
                                         )

                                    ),
				                    					
               );
?>
