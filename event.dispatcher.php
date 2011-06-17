<?php
	/**
	 * Контроллер событий / новый
	 * пытаемся упростить идеологию событий.
	 * Есть классы которые подписываются на catchEvent, есть которые бросают: throwEvent
	 * 
	 */
	class EventDispatcher
	{
		static $Log = array();
		
		static $EventLogMargin = array();
		
		static $CatcherUniqueID = 0;
		
		static $Catchers = array(
			/**
			 'EventName' => array(
			 	'catcher1_id' => Catcher1,
			 	'catcher2_id' => 'Catcher2,
			 	...
			 	'catcherN_id' => 'CatcherN
			 )
			 */
		);
		
		
		public function RegisterCatcher( $EventName, $Catcher )
		{
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Register catcher ' . get_class( $Catcher[0] ) . '->' . $Catcher[1] . ' on event ' . $EventName;
			self::$CatcherUniqueID++;
			if( !array_key_exists( $EventName, self::$Catchers ) ) self::$Catchers[$EventName] = array();
			
			self::$Catchers[$EventName][self::$CatcherUniqueID] = $Catcher;
			
			return self::$CatcherUniqueID;
		}
		
		
		public function RemoveCatcher( $EventName, $Function )
		{
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Register catcher ' . $Function[0] . '->' . $Function[1] . ' on event ' . $EventName;
			
			foreach ( self::$Catchers[$EventName] as $CatcherID => $Catcher )
			{
				$unset = false;
				if( is_array( $Catcher ) && is_array( $Function ) && get_class( $Catcher[0] ) == $Function[0] && $Catcher[1] == $Function[1] )
				{
					$unset = true;
				}
				elseif( !is_array( $Function ) && $Catcher == $Function )
				{
					$unset = true;
				}
				if( $unset )
				{
					unset( self::$Catchers[$EventName][$CatcherID] );
				}
			}			
		}
		
		
		public function ThrowEvent( $EventName, $ThrowerData = null )
		{
			$start_time = microtime( true );
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Throw event ' . $EventName;
			
			self::$EventLogMargin[] = '&nbsp;&nbsp;&nbsp;&nbsp;';
			if( array_key_exists( $EventName, self::$Catchers ) )
			{
				foreach( self::$Catchers[$EventName] as $CacherUniqueID => $Catcher )
				{
					self::$Log[] = join( '', self::$EventLogMargin ) . 'Call catcher ' . get_class( $Catcher[0] ) . '->' . $Catcher[1];
					call_user_func_array( $Catcher, array( $ThrowerData, $CacherUniqueID ) );
				}
			}
			array_pop( self::$EventLogMargin );
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Event ' . $EventName . ' throwed for ' . ceil( (microtime( true )- $start_time ) * 100 ) . ' ms';
		}
		
		public function ClearAllCatchers()
		{
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Clear all catchers';
			self::$Catchers = array();
		}
		
		
		public function ClearEventCatchers( $EventName )
		{
			self::$Log[] = join( '', self::$EventLogMargin ) . 'Clear catchers for event ' . $EventName;
			self::$Catchers[$EventName] = array();
		}
		
		
		public function GetLog()
		{
			return join( '<br/>', self::$Log );
		}
	}
?>
