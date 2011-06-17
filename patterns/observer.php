<?php
	/**
	 * Паттерн обсервер, пока что не протестирован и нигде не используется, по сути черновик
	 *
	 */
	class Observer
	{
		private static $Observers = array();
		
		
		function AddObserver( $Action, $Observer, $RemoveAfterAction = true )
		{
			self::$Observers[$Action][] = $Observer;
			return current( self::$Observers[$Action] );
		}

		function RemoveObserver( $Action, $ObserverID )
		{
			unset( self::$Observers[$Action][$ObserverID] );
			return true;
		}
				
		
		function onObserverAction( $Action, $ArgumentsToObserver )
		{
			
		}
	}

?>