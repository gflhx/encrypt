<?php
   
   class encrypt
   {
      private $key;
      private $uword;
      private $eword;
      private $letters;
      
      /**
       * Constructor.
       * 
       * Creates the $letters array.
       */
      function __construct()
      {
         for($i=0;$i<26;$i++)
         {
            $this->letters[$i][0] = chr(65+$i);
            $this->letters[$i][1] = 0;
         }
         
         for($i=26;$i<52;$i++)
         {
            $this->letters[$i][0] = chr(97+$i-26);
            $this->letters[$i][1] = 0;
         }
         
         for($i=52;$i<62;$i++)
         {
            $this->letters[$i][0] = $i-52;
            $this->letters[$i][1] = 0;
         }
         
         $this->letters[62][0] = ' ';
         $this->letters[62][1] = 0;
      }
      
      /**
       * Encrypts the key using an alternation of the caesar's chiffer.
       * The key is used to set a different alternation for the letters, instead
       * of having it moved by a constant number.
       * 
       * @throws Exception - if there is no key
       * @return string - returns the encrypted alphabet as array
       */
      private function _encrypt()
      {
         if(empty($this->key))
         {
            throw new Exception("Empty key: cannot create encryption without a key.");
         }
         
         $key = $this->key;
         $letters = $this->letters;
         
         $encryption = array_fill(0, 63, '');
         $pos = 0;
         
         //add key to encoding. each letter only once, though
         for($i=0;$i<strlen($key);$i++)
         {
            $c = substr($key, $i, 1);
            $do = true;
            
            //only add letter if it hasn't been used yet
            for($j=0;$j<count($encryption);$j++)
            {
               if(!strcmp($encryption[$j], $c)) $do=false;
            }
            
            if($do) $encryption[$pos] = $c;
            
            //find letters that have already been used
            for($j=0;$j<63;$j++)
            {
               if(!strcmp($letters[$j][0], $encryption[$pos]))
               {
                  $letters[$j][1] = 1;
               }
            }
            
            if($do) $pos++;
         }
         
         
         //add all the unused letters to the encoding array
         $k=63;
         for($i=$pos;$i<63;$i++)
         {
            $continue=true;
            for($j=63-$k;$j<63 && $continue;$j++)
            {
               if($letters[$j][1]==0)
               {
                  $encryption[$i] = $letters[$j][0];
                  $letters[$j][1] = 1;
                  $continue = false;
                  --$k;
               }
            }
         }
         
         //reset letter's usage
         for($i=0;$i<63;$i++)
         {
            $this->letters[$i][1] = 0;
         }
         
         return $encryption;
      }
      
      
      /**
       * Encrypts the given word.
       * 
       * @throws Exception - if there is no unencrypted word
       * @return string - Encrypted word
       */
      public function encrypt()
      {
         if(empty($this->uword))
         {
            throw new Exception("Empty word: cannot encrypt a non-existend word.");
         }
         
         $encryption = $this->_encrypt();
         $uword = $this->uword;
         $eword = '';
         $letters = $this->letters;
         
         for($i=0;$i<strlen($uword);$i++)
         {
            $c = substr($uword, $i, 1);
            
            for($j=0;$j<63;$j++)
            {
               if(!strcmp($letters[$j][0], $c))
               {
                  $eword .= $encryption[$j];
               }
            }
         }
         
         $this->eword = $eword;
         
         return $eword;
      }
      
      
      /**
       * Decrypts the given encryption.
       *
       * @throws Exception - if there is no encrypted word
       * @return string - Encrypted word
       */
      public function decrypt()
      {
         if(empty($this->eword))
         {
            throw new Exception("Empty encryption: cannot decrypt a non-existend word.");
         }
          
         $encryption = $this->_encrypt();
         $eword = $this->eword;
         $uword = '';
         $letters = $this->letters;
         
         for($i=0;$i<strlen($eword);$i++)
         {
            $c = substr($eword, $i, 1);
         
            for($j=0;$j<63;$j++)
            {
               if(!strcmp($encryption[$j], $c))
               {
                  $uword .= $letters[$j][0];
               }
            }
         }
          
         $this->uword = $uword;
             
         return $uword;
      }
      
      
      /**
       * 
       * @param unknown_type $key - keyword for encryption
       */
      public function setKey($key)
      {
         $this->key = $key;
      }
      
      /**
       * 
       * @param unknown_type $uword - unencrypted word
       */
      public function setUword($uword)
      {
         $this->uword = $uword;
      }
      
      /**
       * 
       * @param unknown_type $eword - encrypted word
       */
      public function setEword($eword)
      {
         $this->eword = $eword;
      }
      
      /**
       *
       * @return - $key
       */
      public function getKey()
      {
         return $this->key;
      }
      
      /**
       *
       * @return - $uword
       */
      public function getUword()
      {
         return $this->uword;
      }
      
      /**
       *
       * @return - $eword
       */
      public function getEword()
      {
         return $this->eword;
      }
      
   }




?>